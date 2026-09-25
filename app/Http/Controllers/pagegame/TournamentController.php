<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\TournamentMatch;
use App\Models\TournamentWinner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    public function index()
    {
        // Get active or recent tournaments
        $tournaments = Tournament::with(['winner', 'participants.user'])
            ->orderByRaw("FIELD(status, 'active', 'registration', 'draft', 'completed', 'cancelled')")
            ->orderBy('id', 'desc')
            ->get();

        $userWinners = TournamentWinner::with('tournament')
            ->where('user_id', auth()->id())
            ->get();

        return view('page_game.tournament.index', compact('tournaments', 'userWinners'));
    }

    public function show($id)
    {
        $this->processMatchTimeouts($id);

        $tournament = Tournament::with([
            'participants.user',
            'matches.player1.modelJalur',
            'matches.player2.modelJalur',
            'matches.winner'
        ])->findOrFail($id);

        // Group matches by round
        $matchesByRound = $tournament->matches->groupBy('round');

        // Check if logged in user has an active match
        $currentUserId = auth()->id();
        $activeMatch = TournamentMatch::where('tournament_id', $tournament->id)
            ->where('status', 'ready_check')
            ->where(function ($q) use ($currentUserId) {
                $q->where('player1_id', $currentUserId)
                  ->orWhere('player2_id', $currentUserId);
            })->first();

        return view('page_game.tournament.show', compact('tournament', 'matchesByRound', 'activeMatch'));
    }

    public function readyMatch(Request $request, $matchId)
    {
        $userId = auth()->id();
        $match = TournamentMatch::findOrFail($matchId);

        if ($match->status !== 'ready_check') {
            return response()->json(['success' => false, 'message' => 'Pertandingan belum dalam fase siap.']);
        }

        if ($match->player1_id == $userId) {
            $match->ready_p1 = true;
        } else if ($match->player2_id == $userId) {
            $match->ready_p2 = true;
        } else {
            return response()->json(['success' => false, 'message' => 'Anda bukan pemain di pertandingan ini.']);
        }

        $match->save();

        // Check if both ready
        if ($match->ready_p1 && $match->ready_p2) {
            $match->status = 'in_progress';
            $match->save();

            return response()->json([
                'success' => true,
                'status' => 'start',
                'redirect_url' => route('arena-pacu', ['tournament_match_id' => $match->id])
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'waiting_opponent',
            'message' => 'Menunggu lawan menekan tombol SIAP...'
        ]);
    }

    public function finishMatch(Request $request, $matchId)
    {
        $request->validate([
            'winner_id' => 'required|exists:users,id',
        ]);

        $match = TournamentMatch::with('tournament')->findOrFail($matchId);
        $winnerId = $request->winner_id;

        if ($match->player1_id != $winnerId && $match->player2_id != $winnerId) {
            return response()->json(['success' => false, 'message' => 'Pemenang tidak valid.']);
        }

        $loserId = ($match->player1_id == $winnerId) ? $match->player2_id : $match->player1_id;

        DB::transaction(function () use ($match, $winnerId, $loserId) {
            $match->winner_id = $winnerId;
            $match->status = 'completed';
            $match->save();

            // Mark loser as eliminated
            if ($loserId) {
                TournamentParticipant::where('tournament_id', $match->tournament_id)
                    ->where('user_id', $loserId)
                    ->update(['status' => 'eliminated']);
            }

            // Advance winner to next round match
            $this->advanceWinnerToNextRound($match, $winnerId);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pertandingan selesai!',
            'redirect_url' => route('tournament.show', $match->tournament_id)
        ]);
    }

    private function advanceWinnerToNextRound($match, $winnerId)
    {
        $tournament = $match->tournament;
        $totalRounds = log($tournament->max_participants, 2);

        if ($match->round >= $totalRounds) {
            // Final Match Completed!
            $tournament->status = 'completed';
            $tournament->winner_id = $winnerId;
            $tournament->runner_up_id = ($match->player1_id == $winnerId) ? $match->player2_id : $match->player1_id;
            $tournament->completed_at = now();
            $tournament->save();

            // Update participant winner status
            TournamentParticipant::where('tournament_id', $tournament->id)
                ->where('user_id', $winnerId)
                ->update(['status' => 'winner', 'final_rank' => 1]);

            if ($tournament->runner_up_id) {
                TournamentParticipant::where('tournament_id', $tournament->id)
                    ->where('user_id', $tournament->runner_up_id)
                    ->update(['final_rank' => 2]);
            }

            // Award prize coins to winner
            if ($tournament->prize_coins > 0) {
                $winner = User::find($winnerId);
                if ($winner) {
                    $winner->increment('kuansing_poin', $tournament->prize_coins);
                }
            }

            // Create Hall of Fame Entries
            TournamentWinner::updateOrCreate(
                ['tournament_id' => $tournament->id, 'rank' => 1],
                [
                    'user_id' => $winnerId,
                    'tournament_title' => $tournament->title,
                    'trophy_badge' => 'gold_trophy.png',
                    'prize_coins' => $tournament->prize_coins,
                ]
            );

            if ($tournament->runner_up_id) {
                TournamentWinner::updateOrCreate(
                    ['tournament_id' => $tournament->id, 'rank' => 2],
                    [
                        'user_id' => $tournament->runner_up_id,
                        'tournament_title' => $tournament->title,
                        'trophy_badge' => 'silver_trophy.png',
                        'prize_coins' => 0,
                    ]
                );
            }
        } else {
            // Find target match in next round
            $nextRound = $match->round + 1;
            $targetMatchNumber = ceil($match->match_number / 2);

            $targetMatch = TournamentMatch::where('tournament_id', $tournament->id)
                ->where('round', $nextRound)
                ->where('match_number', $targetMatchNumber)
                ->first();

            if ($targetMatch) {
                // If odd match number, put in player1, if even put in player2
                if ($match->match_number % 2 != 0) {
                    $targetMatch->player1_id = $winnerId;
                } else {
                    $targetMatch->player2_id = $winnerId;
                }
                $targetMatch->save();
            }

            // Activate the NEXT sequential match in the tournament (1 match at a time)
            $this->activateNextSequentialMatch($tournament, $match->round, $match->match_number);
        }
    }

    private function activateNextSequentialMatch($tournament, $currentRound, $currentMatchNumber)
    {
        // 1. Try next match in the same round
        $nextInSameRound = TournamentMatch::where('tournament_id', $tournament->id)
            ->where('round', $currentRound)
            ->where('match_number', $currentMatchNumber + 1)
            ->first();

        if ($nextInSameRound && $nextInSameRound->player1_id && $nextInSameRound->player2_id) {
            $nextInSameRound->status = 'ready_check';
            $nextInSameRound->ready_deadline = Carbon::now()->addMinutes(3);
            $nextInSameRound->save();
            return;
        }

        // 2. If current round is complete, check first match in the next round
        $totalRounds = log($tournament->max_participants, 2);
        if ($currentRound < $totalRounds) {
            $firstInNextRound = TournamentMatch::where('tournament_id', $tournament->id)
                ->where('round', $currentRound + 1)
                ->where('match_number', 1)
                ->first();

            if ($firstInNextRound && $firstInNextRound->player1_id && $firstInNextRound->player2_id) {
                $firstInNextRound->status = 'ready_check';
                $firstInNextRound->ready_deadline = Carbon::now()->addMinutes(3);
                $firstInNextRound->save();
            }
        }
    }

    public function processMatchTimeouts($tournamentId)
    {
        $activeMatches = TournamentMatch::where('tournament_id', $tournamentId)
            ->where('status', 'ready_check')
            ->where('ready_deadline', '<', now())
            ->get();

        foreach ($activeMatches as $match) {
            $winnerId = null;
            if ($match->ready_p1 && !$match->ready_p2) {
                $winnerId = $match->player1_id;
            } else if ($match->ready_p2 && !$match->ready_p1) {
                $winnerId = $match->player2_id;
            } else {
                // Both AFK - forfeit p2 by default, p1 advances
                $winnerId = $match->player1_id ?? $match->player2_id;
            }

            if ($winnerId) {
                $match->winner_id = $winnerId;
                $match->status = 'forfeited';
                $match->save();

                $loserId = ($match->player1_id == $winnerId) ? $match->player2_id : $match->player1_id;
                if ($loserId) {
                    TournamentParticipant::where('tournament_id', $match->tournament_id)
                        ->where('user_id', $loserId)
                        ->update(['status' => 'eliminated']);
                }

                $this->advanceWinnerToNextRound($match, $winnerId);
            }
        }
    }

    public function hallOfFame()
    {
        $winners = TournamentWinner::with(['user', 'tournament'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('page_game.tournament.hall_of_fame', compact('winners'));
    }
}
