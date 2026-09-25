<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelJalur;
use App\Models\Sponsor;
use App\Models\TournamentMatch;

class ArenaPacuController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $modelJalur = ModelJalur::where('user_id', $user->id)->first();
        $modelJalurData = $modelJalur ? ($modelJalur->model_jalur ?? []) : [];
        
        $customColors = $modelJalurData['customColors'] ?? [
            'boat' => '#8b4513',
            'hair' => '#e53e3e',
            'shirt' => '#a0aec0',
            'pants' => '#38a169',
            'paddle' => '#3182ce',
            'splash' => '#a5f3fc'
        ];
        $corakDataUrl = $modelJalurData['corak_data_url'] ?? null;
        $lambaiDataUrl = $modelJalurData['lambai_data_url'] ?? null;

        $sponsors = Sponsor::where('is_active', true)->pluck('image_path')->map(fn($p) => asset($p))->toArray();

        // Check if tournament match
        $tournamentMatch = null;
        $isSpectator = false;
        if ($request->filled('tournament_match_id')) {
            $tournamentMatch = TournamentMatch::with(['player1.modelJalur', 'player2.modelJalur'])->find($request->tournament_match_id);
            if ($request->input('mode') === 'spectator') {
                $isSpectator = true;
            }
        }

        return view('page_game.arenapacu.index', compact(
            'customColors', 
            'corakDataUrl', 
            'lambaiDataUrl', 
            'sponsors',
            'tournamentMatch',
            'isSpectator'
        ));
    }

    public function addCoins(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->kuansing_poin += intval($request->input('coins', 0));
            $user->save();
        }
        return response()->json(['success' => true, 'coins' => $user->kuansing_poin]);
    }
}
