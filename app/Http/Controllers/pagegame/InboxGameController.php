<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use App\Models\Inbox;
use App\Models\UserInboxStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InboxGameController extends Controller
{
    public function list()
    {
        $userId = auth()->id();

        // Get active inboxes for all or targeted to this user
        $inboxes = Inbox::where('is_active', true)
            ->where(function ($query) use ($userId) {
                $query->where('target_type', 'all')
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('target_type', 'user')
                          ->where('target_user_id', $userId);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get status records for this user
        $statuses = UserInboxStatus::where('user_id', $userId)
            ->get()
            ->keyBy('inbox_id');

        $result = [];
        $unreadCount = 0;

        foreach ($inboxes as $inbox) {
            $status = $statuses->get($inbox->id);

            // Skip if user deleted this inbox
            if ($status && $status->is_deleted) {
                continue;
            }

            $isRead = $status ? (bool)$status->is_read : false;
            $isClaimed = $status ? (bool)$status->is_claimed : false;

            if (!$isRead) {
                $unreadCount++;
            }

            $result[] = [
                'id' => $inbox->id,
                'title' => $inbox->title,
                'content' => $inbox->content,
                'type' => $inbox->type ?? 'info',
                'reward_coins' => (int)$inbox->reward_coins,
                'target_type' => $inbox->target_type,
                'created_at_formatted' => $inbox->created_at->format('d M Y H:i'),
                'is_read' => $isRead,
                'is_claimed' => $isClaimed,
            ];
        }

        return response()->json([
            'success' => true,
            'inboxes' => $result,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $userId = auth()->id();
        $inbox = Inbox::findOrFail($id);

        UserInboxStatus::updateOrCreate(
            ['inbox_id' => $inbox->id, 'user_id' => $userId],
            ['is_read' => true, 'read_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function claimReward($id)
    {
        $userId = auth()->id();
        $user = auth()->user();
        $inbox = Inbox::findOrFail($id);

        if ($inbox->reward_coins <= 0) {
            return response()->json(['success' => false, 'message' => 'Pesan ini tidak memiliki hadiah koin.'], 400);
        }

        return DB::transaction(function () use ($inbox, $userId, $user) {
            $status = UserInboxStatus::firstOrCreate(
                ['inbox_id' => $inbox->id, 'user_id' => $userId],
                ['is_read' => false, 'is_claimed' => false, 'is_deleted' => false]
            );

            if ($status->is_claimed) {
                return response()->json(['success' => false, 'message' => 'Hadiah koin sudah diklaim sebelumnya.'], 400);
            }

            $status->is_claimed = true;
            $status->is_read = true;
            $status->claimed_at = now();
            $status->read_at = now();
            $status->save();

            $user->kuansing_poin += $inbox->reward_coins;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengklaim ' . number_format($inbox->reward_coins, 0, ',', '.') . ' Koin Sprint!',
                'new_coins' => (int)$user->kuansing_poin,
            ]);
        });
    }

    public function deleteInbox($id)
    {
        $userId = auth()->id();
        $inbox = Inbox::findOrFail($id);

        UserInboxStatus::updateOrCreate(
            ['inbox_id' => $inbox->id, 'user_id' => $userId],
            ['is_deleted' => true]
        );

        return response()->json(['success' => true]);
    }
}
