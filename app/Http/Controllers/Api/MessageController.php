<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\MessageRepositoryInterface;
use App\Models\Matchup;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageRepository;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function showConversation($otherUserId, Request $request)
    {
        try {
            $userId = $request->user()->id; 
            $page = $request->query('page', 1); // Sayfa numarası
            $perPage = $request->query('per_page', 10); // Sayfa başına mesaj sayısı
            
            $messages = $this->messageRepository->getConversationWithUser($userId, $otherUserId, $page, $perPage);

            $formattedMessages = $messages->map(function($message) use ($userId) {
                return [
                    'content' => $message->content,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        // 'profile_picture' => $message->sender->profileDetail->profile_picture ?? null,
                    ],
                    'receiver' => [
                        'id' => $message->receiver->id,
                        'name' => $message->receiver->name,
                        // 'profile_picture' => $message->receiver->profileDetail->profile_picture ?? null,
                    ],
                    'timestamp' => $message->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Conversation retrieved successfully',
                'data' => $formattedMessages,
                'errors' => null
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve conversation',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    public function conversations(Request $request)
    {
        try {
            $userId = $request->user()->id; 
            $page = $request->query('page', 1); 
            $perPage = $request->query('per_page', 10); 

            $conversations = $this->messageRepository->getConversationsByUserId($userId, $page, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Conversations retrieved successfully',
                'data' => $conversations,
                'errors' => null
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve conversations',
                'data' => null,
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
