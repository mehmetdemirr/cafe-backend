<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Interfaces\MessageRepositoryInterface;
use App\Models\Matchup;
use Illuminate\Http\JsonResponse;
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

    public function sendMessage(MessageRequest $request): JsonResponse
    {
        // Şu an giriş yapmış kullanıcıyı sender olarak alıyoruz
        $userId = $request->user()->id;
        $receiverId = $request->input('receiver_id');
        
        // Mesajı gönder
        $result = $this->messageRepository->sendMessage($userId, $receiverId, $request->only(['content', 'media_path']));

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => "Gönderici ya da alıcı bulunamadı !",
                'data' => null,
                'errors' => null,
            ], 400);
        }

        // Kanal adını oluştur (ID'lere göre sabit bir kanal yapısı)
        $channelName = 'chat.' . min($userId, $receiverId) . '.' . max($userId, $receiverId);

        // Event tetikleme (Mesajı yayınla)
        MessageSent::dispatch($channelName, $userId, $receiverId, $request->input('content'));

        return response()->json([
            'success' => true,
            'data' => true,
            'message' => 'Mesaj başarıyla gönderildi.',
            'errors' => null,
        ], 200); 
    }
}
