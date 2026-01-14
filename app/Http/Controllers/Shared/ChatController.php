<?php

namespace App\Http\Controllers\Shared;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Services\Shared\ChatService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ConversationResource;

class ChatController extends Controller
{
    use ApiResponse;

    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(SendMessageRequest $request)
    {
        try {
            $chat = $this->chatService->sendMessage(
                Auth::user(),
                $request->receiver_id,
                $request->message
            );

            if (!$chat) {
                return $this->error('Failed to send message', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return $this->success(new ChatResource($chat), 'Message sent successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getConversation(int $userId)
    {
        try {
            $messages = $this->chatService->getConversation(Auth::user(), $userId);

            return $this->success([
                'messages' => ChatResource::collection($messages),
                'pagination' => [
                    'current_page' => $messages->currentPage(),
                    'last_page' => $messages->lastPage(),
                    'per_page' => $messages->perPage(),
                    'total' => $messages->total(),
                ],
            ], 'Conversation retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getConversations()
    {
        try {
            $conversations = $this->chatService->getConversations(Auth::user());

            return $this->success([
                'conversations' => ConversationResource::collection($conversations),
            ], 'Conversations retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function markAsRead(int $userId)
    {
        try {
            $count = $this->chatService->markAsRead(Auth::user(), $userId);

            return $this->success([
                'marked_count' => $count,
            ], 'Messages marked as read');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getUnreadCount()
    {
        try {
            $count = $this->chatService->getUnreadCount(Auth::user());

            return $this->success([
                'unread_count' => $count,
            ], 'Unread count retrieved successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
