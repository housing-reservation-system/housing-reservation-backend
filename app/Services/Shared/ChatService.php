<?php

namespace App\Services\Shared;

use App\Models\Chat;
use App\Models\User;
use App\Events\MessageSent;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatService
{
    public function sendMessage(User $sender, int $receiverId, string $message): ?Chat
    {
        try {
            DB::beginTransaction();

            $chat = Chat::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiverId,
                'message' => $message,
                'is_read' => false,
            ]);

            $chat->load(['sender', 'receiver']);

            broadcast(new MessageSent($chat))->toOthers();

            $receiver = User::find($receiverId);
            if ($receiver) {
                $receiver->notify(new NewMessageNotification($chat, $sender));
            }

            DB::commit();

            return $chat;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to send message: ' . $e->getMessage());
            return null;
        }
    }

    public function getConversation(User $user, int $otherUserId, int $perPage = 50)
    {
        return Chat::where(function ($query) use ($user, $otherUserId) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $otherUserId);
        })
            ->orWhere(function ($query) use ($user, $otherUserId) {
                $query->where('sender_id', $otherUserId)
                    ->where('receiver_id', $user->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getConversations(User $user)
    {
        $conversations = DB::table('chats')
            ->select(
                DB::raw('CASE
                    WHEN sender_id = ' . $user->id . ' THEN receiver_id
                    ELSE sender_id
                END as other_user_id'),
                DB::raw('MAX(created_at) as last_message_time')
            )
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->groupBy('other_user_id')
            ->orderBy('last_message_time', 'desc')
            ->get();

        $result = [];
        foreach ($conversations as $conversation) {
            $otherUser = User::find($conversation->other_user_id);
            if (!$otherUser) continue;

            $lastMessage = Chat::where(function ($query) use ($user, $otherUser) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $otherUser->id);
            })
                ->orWhere(function ($query) use ($user, $otherUser) {
                    $query->where('sender_id', $otherUser->id)
                        ->where('receiver_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            $unreadCount = Chat::where('sender_id', $otherUser->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();

            $result[] =  (object) [
                'other_user' => $otherUser,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
            ];
        }

        return $result;
    }

    public function markAsRead(User $user, int $otherUserId): int
    {
        return Chat::where('sender_id', $otherUserId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getUnreadCount(User $user): int
    {
        return Chat::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
    }
}
