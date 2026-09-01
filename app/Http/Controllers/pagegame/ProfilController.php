<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        $winsCount = $user->wins()->count();
        $lossesCount = $user->losses()->count();

        $statusText = 'ANAK BARU';
        if ($winsCount >= 100) {
            $statusText = 'PAMACU INTI';
        } elseif ($winsCount >= 50) {
            $statusText = 'PAMAIN SEWA';
        }

        // Fetch match history (riwayat permainan) optimized query
        $history = \App\Models\Room::where('status', 'finished')
            ->where(function($query) use ($userId) {
                $query->where('host_id', $userId)
                      ->orWhere('guest_id', $userId);
            })
            ->select(['id', 'room_code', 'host_id', 'guest_id', 'winner_id', 'name', 'status', 'updated_at'])
            ->with([
                'host:id,nama_jalur,email',
                'guest:id,nama_jalur,email',
                'winner:id,nama_jalur,email'
            ])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('page_game.profil.index', compact('user', 'winsCount', 'lossesCount', 'statusText', 'history'));
    }
}
