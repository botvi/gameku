<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use App\Models\ModelJalur;
use App\Models\MatchmakingQueue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RoomController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            MatchmakingQueue::where('user_id', auth()->id())->delete();
        }
        return view('page_game.room.index');
    }

    public function createOrJoin()
    {
        return view('page_game.room.createorjoin');
    }

    /**
     * Hapus room yang sudah expired (lama & masih waiting, tanpa guest).
     * Room yang sudah finished / sudah ada guest tidak dihapus.
     */
    protected function cleanupExpiredRooms()
    {
        // Room waiting yang sudah lebih dari 30 menita dan belum ada guest -> expired
        Room::where('status', 'waiting')
            ->whereNull('guest_id')
            ->where('updated_at', '<', now()->subMinutes(30))
            ->delete();
    }

    public function list()
    {
        // Hapus room yang sudah expired (lama & masih waiting)
        $this->cleanupExpiredRooms();

        $rooms = Room::where('status', 'waiting')
            ->whereNull('guest_id')
            ->where('host_id', '!=', auth()->id())
            ->with('host')
            ->orderBy('created_at', 'desc')
            ->get()
            // Hanya tampilkan 1 room per host (room yang paling baru)
            ->unique('host_id');

        return response()->json([
            'rooms' => $rooms->map(function($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'is_private' => !empty($room->password),
                    'host_name' => $room->host->nama_jalur ?? $room->host->email,
                ];
            })
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'password' => 'nullable|string|max:50',
        ]);

        // Jika player sudah memiliki room aktif sebagai host, reuse room tersebut
        // (tidak perlu hapus di database — room lama tetap ada tapi tidak diduplikat)
        $existingRoom = Room::where('host_id', auth()->id())
            ->where('status', 'waiting')
            ->first();

        if ($existingRoom) {
            // Update nama & password room yang sudah ada
            $existingRoom->name = $request->name;
            $existingRoom->password = $request->password ? Hash::make($request->password) : null;
            $existingRoom->save();

            return response()->json([
                'success' => true,
                'redirect_url' => route('room.lobby', ['id' => $existingRoom->id])
            ]);
        }

        $room = Room::create([
            'room_code' => strtoupper(Str::random(6)),
            'name' => $request->name,
            'password' => $request->password ? Hash::make($request->password) : null,
            'host_id' => auth()->id(),
            'status' => 'waiting',
        ]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('room.lobby', ['id' => $room->id])
        ]);
    }

    public function join(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'password' => 'nullable|string',
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($room->host_id === auth()->id()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('room.lobby', ['id' => $room->id])
            ]);
        }

        if (!empty($room->password)) {
            if (!$request->password || !Hash::check($request->password, $room->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password room salah!'
                ], 422);
            }
        }

        if (!empty($room->guest_id) && $room->guest_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Room sudah penuh!'
            ], 422);
        }

        $room->guest_id = auth()->id();
        $room->save();

        return response()->json([
            'success' => true,
            'redirect_url' => route('room.lobby', ['id' => $room->id])
        ]);
    }

    public function joinByCode(Request $request)
    {
        $request->validate([
            'room_code' => 'required|string|max:10',
        ]);

        $room = Room::where('room_code', strtoupper($request->room_code))
            ->where('status', 'waiting')
            ->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Kode room tidak ditemukan atau sudah tidak aktif.'
            ], 404);
        }

        // Host sudah ada di dalam, langsung redirect
        if ($room->host_id === auth()->id()) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('room.lobby', ['id' => $room->id])
            ]);
        }

        // Room sudah penuh
        if (!empty($room->guest_id) && $room->guest_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Room sudah penuh!'
            ], 422);
        }

        // Room private — minta password
        if (!empty($room->password)) {
            return response()->json([
                'success' => false,
                'requires_password' => true,
                'room_id' => $room->id,
                'room_name' => $room->name,
            ]);
        }

        // Langsung masuk
        $room->guest_id = auth()->id();
        $room->save();

        return response()->json([
            'success' => true,
            'redirect_url' => route('room.lobby', ['id' => $room->id])
        ]);
    }

    public function matchmake(Request $request)
    {
        $userId = auth()->id();

        // Clean up old stale queue entries older than 2 minutes
        MatchmakingQueue::where('updated_at', '<', now()->subMinutes(2))->delete();

        // Remove any existing active queue for this user
        MatchmakingQueue::where('user_id', $userId)->delete();

        // Check if there is another player currently searching (active within last 15 seconds)
        $opponentQueue = MatchmakingQueue::where('status', 'searching')
            ->where('user_id', '!=', $userId)
            ->where('updated_at', '>=', now()->subSeconds(15))
            ->orderBy('created_at', 'asc')
            ->first();

        if ($opponentQueue) {
            // Match found! Create a room for both players
            $room = Room::create([
                'room_code' => strtoupper(Str::random(6)),
                'name' => 'Quick Match',
                'host_id' => $opponentQueue->user_id,
                'guest_id' => $userId,
                'status' => 'waiting',
            ]);

            // Update opponent queue to matched
            $opponentQueue->update([
                'status' => 'matched',
                'room_id' => $room->id,
            ]);

            // Create current user queue entry as matched
            MatchmakingQueue::create([
                'user_id' => $userId,
                'status' => 'matched',
                'room_id' => $room->id,
            ]);

            return response()->json([
                'success' => true,
                'status' => 'matched',
                'redirect_url' => route('room.lobby', ['id' => $room->id])
            ]);
        } else {
            // Tidak ada lawan online -> match dengan bot AI random
            $bot = $this->findRandomBot();

            if ($bot) {
                $room = Room::create([
                    'room_code' => strtoupper(Str::random(6)),
                    'name' => 'Quick Match',
                    'host_id' => $userId,
                    'guest_id' => $bot->id,
                    'status' => 'waiting',
                ]);

                return response()->json([
                    'success' => true,
                    'status' => 'matched',
                    'redirect_url' => route('room.lobby', ['id' => $room->id])
                ]);
            }

            // Fallback: masuk queue dan wait (tidak ada bot)
            MatchmakingQueue::create([
                'user_id' => $userId,
                'status' => 'searching',
            ]);

            return response()->json([
                'success' => true,
                'status' => 'searching',
                'message' => 'Mencari lawan...'
            ]);
        }
    }

    /**
     * Pilih bot AI random dari database.
     */
    protected function findRandomBot()
    {
        $bots = User::where('is_bot', true)->get();
        if ($bots->isEmpty()) {
            return null;
        }
        return $bots->random();
    }

    public function matchmakeStatus(Request $request)
    {
        $userId = auth()->id();

        $queue = MatchmakingQueue::where('user_id', $userId)->first();

        if (!$queue) {
            return response()->json([
                'success' => false,
                'status' => 'cancelled'
            ]);
        }

        // If currently searching, update timestamp so server knows player is alive
        if ($queue->status === 'searching') {
            $queue->touch();
            return response()->json([
                'success' => true,
                'status' => 'searching'
            ]);
        }

        // If matched, return redirect_url
        if ($queue->status === 'matched' && $queue->room_id) {
            $redirectUrl = route('room.lobby', ['id' => $queue->room_id]);
            // Clean up queue entry after retrieving match
            $queue->delete();

            return response()->json([
                'success' => true,
                'status' => 'matched',
                'redirect_url' => $redirectUrl
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => $queue->status
        ]);
    }

    public function matchmakeCancel(Request $request)
    {
        $userId = auth()->id();

        MatchmakingQueue::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pencarian lawan dibatalkan.'
        ]);
    }

    public function lobby($id)
    {
        $room = Room::with(['host', 'guest'])->findOrFail($id);

        // Security check: only host and guest can view the lobby
        if ($room->host_id !== auth()->id() && (!empty($room->guest_id) && $room->guest_id !== auth()->id())) {
            return redirect()->route('room')->with('error', 'Anda tidak memiliki akses ke room ini.');
        }

        // Bot customizations (if guest is a bot)
        $botCustomizations = [];
        if ($room->guest && $room->guest->is_bot) {
            $modelJalur = ModelJalur::where('user_id', $room->guest_id)->first();
            $modelJalurData = $modelJalur ? ($modelJalur->model_jalur ?? []) : [];
            $botCustomizations = [
                'colors' => $modelJalurData['customColors'] ?? [
                    'boat' => '#d97706',
                    'hair' => '#2563eb',
                    'shirt' => '#ea580c',
                    'pants' => '#4b5563',
                    'paddle' => '#854d0e',
                    'splash' => '#a5f3fc',
                ],
                'corak_data_url' => $modelJalurData['corak_data_url'] ?? null,
                'lambai_data_url' => $modelJalurData['lambai_data_url'] ?? null,
            ];
        }

        return view('page_game.room.lobby', compact('room', 'botCustomizations'));
    }

    public function ready(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'ready' => 'required|boolean',
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($room->host_id === auth()->id()) {
            $room->host_ready = $request->ready;
        } elseif ($room->guest_id === auth()->id()) {
            $room->guest_ready = $request->ready;
        } else {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $room->save();

        return response()->json([
            'success' => true,
            'host_ready' => $room->host_ready,
            'guest_ready' => $room->guest_ready,
        ]);
    }

    public function leave(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $room = Room::findOrFail($request->room_id);

        if ($room->host_id === auth()->id()) {
            // Host leaves -> delete the room
            $room->delete();
            return response()->json([
                'success' => true,
                'redirect_url' => route('room')
            ]);
        } elseif ($room->guest_id === auth()->id()) {
            // Guest leaves -> vacate slot and reset ready states
            $room->guest_id = null;
            $room->guest_ready = false;
            $room->host_ready = false;
            $room->save();
            return response()->json([
                'success' => true,
                'redirect_url' => route('room')
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    public function finish(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'winner_id' => 'required|exists:users,id',
        ]);

        $room = Room::findOrFail($request->room_id);

        // Prevent duplicate rewards / state changes
        if ($room->status === 'finished') {
            return response()->json([
                'success' => true,
                'message' => 'Room match has already been finished.'
            ]);
        }

        $room->status = 'finished';
        $room->winner_id = $request->winner_id;
        $room->loser_id = ($request->winner_id == $room->host_id) ? $room->guest_id : $room->host_id;
        $room->save();

        return response()->json([
            'success' => true,
            'message' => 'Match results saved successfully.'
        ]);
    }
}
