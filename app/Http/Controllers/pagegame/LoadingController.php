<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use App\Models\ModelJalur;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoadingController extends Controller
{
    public function index()
    {
        return view('page_game.loading.index');
    }

    public function syncData(): JsonResponse
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $modelJalur = ModelJalur::where('user_id', $user->id)->first();
        $modelData = $modelJalur ? ($modelJalur->model_jalur ?? []) : [];

        // Determine boat & lambai unlock status
        if (array_key_exists('boat_unlocked', $modelData)) {
            $boatUnlocked = filter_var($modelData['boat_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $boatUnlocked = $modelJalur ? ($modelJalur->fitur_corak === 'active') : false;
        }

        if (array_key_exists('lambai_unlocked', $modelData)) {
            $lambaiUnlocked = filter_var($modelData['lambai_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $lambaiUnlocked = $modelJalur ? ($modelJalur->fitur_lambai === 'active') : false;
        }

        $customColors = $modelData['customColors'] ?? [
            'boat' => '#8b4513',
            'hair' => '#e53e3e',
            'shirt' => '#a0aec0',
            'pants' => '#38a169',
            'paddle' => '#3182ce',
            'splash' => '#a5f3fc'
        ];

        $corakDataUrl = $modelData['corak_data_url'] ?? null;
        $lambaiDataUrl = $modelData['lambai_data_url'] ?? null;
        $vsaiUnlocked = $modelData['vsai_unlocked'] ?? 1;

        // Critical static assets to preload in browser cache
        $criticalAssets = [
            asset('game_pacu/assets/image/bg/bgmenu.jpg'),
            asset('game_pacu/assets/image/ui/back.png'),
            asset('game_pacu/assets/image/ui/sprint.png'),
            asset('game_pacu/assets/image/ui/piala.png'),
            asset('game_pacu/assets/image/ui/sound_on.png'),
            asset('game_pacu/assets/image/ui/sound_off.png'),
            asset('env/logo_text1.png'),
        ];

        if ($user->foto_profile) {
            $criticalAssets[] = str_starts_with($user->foto_profile, 'http')
                ? $user->foto_profile
                : asset($user->foto_profile);
        }

        if ($corakDataUrl && !str_starts_with($corakDataUrl, 'data:')) {
            $criticalAssets[] = str_starts_with($corakDataUrl, 'http') ? $corakDataUrl : asset($corakDataUrl);
        }

        if ($lambaiDataUrl && !str_starts_with($lambaiDataUrl, 'data:')) {
            $criticalAssets[] = str_starts_with($lambaiDataUrl, 'http') ? $lambaiDataUrl : asset($lambaiDataUrl);
        }

        return response()->json([
            'success' => true,
            'app_version' => config('app.version', '1.0.5'),
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'nama_jalur' => $user->nama_jalur ?? 'Jalur Kuansing',
                'coins' => (int) ($user->kuansing_poin ?? 0),
                'foto_profile' => $user->foto_profile ?? 'profiles/default.gif',
            ],
            'customization' => [
                'customColors' => $customColors,
                'corak_data_url' => $corakDataUrl,
                'lambai_data_url' => $lambaiDataUrl,
                'boat_unlocked' => (bool) $boatUnlocked,
                'lambai_unlocked' => (bool) $lambaiUnlocked,
                'vsai_unlocked' => (int) $vsaiUnlocked,
            ],
            'critical_assets' => array_values(array_unique($criticalAssets)),
        ]);
    }
}
