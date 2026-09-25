<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\TournamentMatch;
use App\Models\TournamentWinner;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TournamentAdminController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::with(['winner', 'creator', 'participants'])->orderBy('id', 'desc')->paginate(10);
        return view('pagesuperadmin.tournaments.index', compact('tournaments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:2|max:128',
            'prize_coins' => 'nullable|integer|min:0',
        ]);

        $tournament = Tournament::create([
            'title' => $request->title,
            'description' => $request->description ?? 'Turnamen Resmi Pacu Jalur: The Pixel',
            'max_participants' => $request->max_participants,
            'prize_coins' => $request->prize_coins ?? 0,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('superadmin.tournaments.detail', $tournament->id)->with('success', 'Turnamen berhasil dibuat. Silakan pilih peserta.');
    }

    public function detail($id)
    {
        $tournament = Tournament::with(['participants.user', 'matches.player1', 'matches.player2', 'matches.winner'])->findOrFail($id);
        $users = User::where('role', '!=', 'admin')->orderBy('nama_jalur', 'asc')->get();
        return view('pagesuperadmin.tournaments.detail', compact('tournament', 'users'));
    }

    public function addParticipants(Request $request, $id)
    {
        $tournament = Tournament::findOrFail($id);
        if ($tournament->status !== 'draft') {
            return back()->with('error', 'Peserta hanya dapat diubah pada status Draft.');
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        if (count($request->user_ids) > $tournament->max_participants) {
            return back()->with('error', "Jumlah peserta terpilih melampaui kuota maksimal ({$tournament->max_participants}).");
        }

        // Reset existing participants
        TournamentParticipant::where('tournament_id', $tournament->id)->delete();

        $seed = 1;
        foreach ($request->user_ids as $userId) {
            TournamentParticipant::create([
                'tournament_id' => $tournament->id,
                'user_id' => $userId,
                'seed_number' => $seed++,
                'status' => 'active',
            ]);
        }

        return back()->with('success', 'Peserta turnamen berhasil disimpan.');
    }

    public function generateBracket($id)
    {
        $tournament = Tournament::with('participants')->findOrFail($id);

        $participants = $tournament->participants->shuffle()->values();
        $total = $participants->count();

        if ($total < 2) {
            return back()->with('error', 'Jumlah peserta minimal 2 pemain untuk membuat bagan turnamen.');
        }

        // Clear existing matches
        TournamentMatch::where('tournament_id', $tournament->id)->delete();

        // Calculate upper power of 2 for single elimination bracket (e.g. 5 -> 8, 3 -> 4)
        $powerOfTwo = pow(2, ceil(log($total, 2)));
        if ($powerOfTwo < 4) $powerOfTwo = 4; // minimum 4 slot bracket structure
        $totalRounds = log($powerOfTwo, 2);

        // 1. Create Empty Matches for All Rounds
        $roundMatchesCount = $powerOfTwo / 2;
        for ($r = 1; $r <= $totalRounds; $r++) {
            $mNum = 1;
            for ($m = 0; $m < $roundMatchesCount; $m++) {
                TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'round' => $r,
                    'match_number' => $mNum++,
                    'player1_id' => null,
                    'player2_id' => null,
                    'status' => 'waiting_players',
                    'room_id' => 'tn_' . $tournament->id . '_r' . $r . '_m' . $mNum . '_' . Str::random(6),
                ]);
            }
            $roundMatchesCount /= 2;
        }

        // 2. Fill Round 1 Matches with Participants
        $round1Matches = TournamentMatch::where('tournament_id', $tournament->id)
            ->where('round', 1)
            ->orderBy('match_number', 'asc')
            ->get();

        $pIdx = 0;
        foreach ($round1Matches as $match) {
            $p1 = isset($participants[$pIdx]) ? $participants[$pIdx]->user_id : null;
            $pIdx++;
            $p2 = isset($participants[$pIdx]) ? $participants[$pIdx]->user_id : null;
            $pIdx++;

            $match->player1_id = $p1;
            $match->player2_id = $p2;

            // Handle BYE pass: If player1 exists but no player2, player1 automatically advances!
            if ($p1 && !$p2) {
                $match->status = 'completed';
                $match->winner_id = $p1;
                $match->save();

                // Advance BYE winner to next round target match
                $targetMatchNumber = ceil($match->match_number / 2);
                $targetMatch = TournamentMatch::where('tournament_id', $tournament->id)
                    ->where('round', 2)
                    ->where('match_number', $targetMatchNumber)
                    ->first();

                if ($targetMatch) {
                    if ($match->match_number % 2 != 0) {
                        $targetMatch->player1_id = $p1;
                    } else {
                        $targetMatch->player2_id = $p1;
                    }
                    $targetMatch->save();
                }
            } else {
                $match->save();
            }
        }

        $tournament->update(['status' => 'registration']);

        return back()->with('success', "Bagan pertandingan ({$total} Peserta, System Gugur dengan BYE Pass) berhasil di-generate!");
    }

    public function startTournament($id)
    {
        $tournament = Tournament::findOrFail($id);
        if ($tournament->status !== 'registration') {
            return back()->with('error', 'Turnamen hanya dapat dimulai dari status Registration / Bracket Ready.');
        }

        $tournament->update([
            'status' => 'active',
            'started_at' => now(),
        ]);

        // Activate ONLY the FIRST playable match (with both players) sequentially
        $firstMatch = TournamentMatch::where('tournament_id', $tournament->id)
            ->where('round', 1)
            ->where('status', 'waiting_players')
            ->whereNotNull('player1_id')
            ->whereNotNull('player2_id')
            ->orderBy('match_number', 'asc')
            ->first();

        if (!$firstMatch) {
            // Check Round 2 if all Round 1 had BYEs
            $firstMatch = TournamentMatch::where('tournament_id', $tournament->id)
                ->where('round', 2)
                ->where('status', 'waiting_players')
                ->whereNotNull('player1_id')
                ->whereNotNull('player2_id')
                ->orderBy('match_number', 'asc')
                ->first();
        }

        if ($firstMatch) {
            $firstMatch->update([
                'status' => 'ready_check',
                'ready_deadline' => Carbon::now()->addMinutes(3),
            ]);
        }

        return back()->with('success', 'Turnamen resmi DIMULAI! Match 1 (Laga Pertama) kini aktif dalam status Ready Check (Batas 3 Menit).');
    }

    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournament->delete();
        return redirect()->route('superadmin.tournaments')->with('success', 'Turnamen telah dihapus.');
    }
}
