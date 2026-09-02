<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use App\Models\ModelJalur;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class TukangJaluarController extends Controller
{
    public function index()
    {
        return view('page_game.tukangjaluar.index');
    }
    public function save(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $data = $request->all();

        // Update user's kuansing_poin if sent in the request
        if (isset($data['coins'])) {
            $user->kuansing_poin = intval($data['coins']);
            $user->save();
            // Remove coins from the JSON model_jalur
            unset($data['coins']);
        }

        $existing = ModelJalur::where('user_id', $user->id)->first();
        $existingData = $existing ? ($existing->model_jalur ?? []) : [];

        // If request specifies boat_unlocked, use request value. Otherwise fallback to existing JSON / DB column.
        if (array_key_exists('boat_unlocked', $data)) {
            $boatUnlocked = filter_var($data['boat_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } elseif (array_key_exists('boat_unlocked', $existingData)) {
            $boatUnlocked = filter_var($existingData['boat_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $boatUnlocked = $existing && $existing->fitur_corak === 'active';
        }

        // If request specifies lambai_unlocked, use request value. Otherwise fallback to existing JSON / DB column.
        if (array_key_exists('lambai_unlocked', $data)) {
            $lambaiUnlocked = filter_var($data['lambai_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } elseif (array_key_exists('lambai_unlocked', $existingData)) {
            $lambaiUnlocked = filter_var($existingData['lambai_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $lambaiUnlocked = $existing && $existing->fitur_lambai === 'active';
        }

        // Explicitly set boolean true/false in JSON data payload
        $data['boat_unlocked'] = (bool) $boatUnlocked;
        $data['lambai_unlocked'] = (bool) $lambaiUnlocked;

        // Save customization details to database model_jalurs table
        $modelJalur = ModelJalur::updateOrCreate(
            ['user_id' => $user->id],
            [
                'model_jalur' => $data,
                'fitur_corak' => $boatUnlocked ? 'active' : 'inactive',
                'fitur_lambai' => $lambaiUnlocked ? 'active' : 'inactive',
            ]
        );

        return response()->json(['success' => true]);
    }

    public function get()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $modelJalur = ModelJalur::where('user_id', $user->id)->first();
        
        $data = $modelJalur ? ($modelJalur->model_jalur ?? []) : [];
        
        // Ensure coins is set from the user's kuansing_poin, NOT from JSON
        $data['coins'] = $user->kuansing_poin;
        $data['nama_jalur'] = $user->nama_jalur;
        
        // Read lock status directly from JSON model_jalur (if key exists) or DB column
        if (array_key_exists('boat_unlocked', $data)) {
            $boatUnlocked = filter_var($data['boat_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $boatUnlocked = $modelJalur ? ($modelJalur->fitur_corak === 'active') : false;
        }

        if (array_key_exists('lambai_unlocked', $data)) {
            $lambaiUnlocked = filter_var($data['lambai_unlocked'], FILTER_VALIDATE_BOOLEAN);
        } else {
            $lambaiUnlocked = $modelJalur ? ($modelJalur->fitur_lambai === 'active') : false;
        }

        $data['boat_unlocked'] = (bool) $boatUnlocked;
        $data['lambai_unlocked'] = (bool) $lambaiUnlocked;
        
        if (empty($data['customColors'])) {
            $data['customColors'] = [
                'boat' => '#8b4513',
                'hair' => '#e53e3e',
                'shirt' => '#a0aec0',
                'pants' => '#38a169',
                'paddle' => '#3182ce',
                'splash' => '#a5f3fc'
            ];
        }

        return response()->json($data);
    }

    public function uploadCorak(Request $request): JsonResponse
    {
        return $this->uploadGameImage($request, 'corak');
    }

    public function uploadLambai(Request $request): JsonResponse
    {
        return $this->uploadGameImage($request, 'lambai');
    }

    private function uploadGameImage(Request $request, string $folder): JsonResponse
    {
        if (!auth()->id()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            $validated = $request->validate([
                'file' => 'required|file|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            ]);

            $file = $validated['file'];
            $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png');
            $filename = time() . '_' . Str::random(10) . '.' . $extension;

            $disk = Storage::disk('public');
            $stored = $disk->putFileAs($folder, $file, $filename);

            if (!$stored) {
                return response()->json([
                    'error' => 'Gagal menyimpan file. Pastikan folder storage dapat ditulis.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'url' => '/storage/' . $folder . '/' . $filename,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => collect($e->errors())->flatten()->first() ?: 'File tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            Log::error("Upload {$folder} gagal", [
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => app()->hasDebugModeEnabled()
                    ? $e->getMessage()
                    : 'Gagal mengunggah file. Silakan coba lagi atau hubungi admin.',
            ], 500);
        }
    }
}

