<?php

namespace App\Repositories;
use App\Interfaces\MessageRepositoryInterface;
use App\Models\Matchup;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{
    public function getConversationWithUser(int $userId, int $otherUserId, int $page = 1, int $perPage = 10)
    {
        return Message::where(function ($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $otherUserId)
                      ->where('receiver_id', $userId);
            })
            // ->with(['sender.profileDetail', 'receiver.profileDetail'])
            ->orderBy('created_at', 'desc')
            ->forPage($page, $perPage) // Sayfalama için
            ->paginate($perPage);
    }
    
    public function getConversationsByUserId(int $userId, int $page = 1, int $perPage = 10)
    {
        // Eşleşmeleri al
        $matchedUsers = Matchup::where('user1_id', $userId)
            ->orWhere('user2_id', $userId)
            ->with(['user1', 'user2'])
            ->get();

        $messages = [];

        foreach ($matchedUsers as $match) {
            $otherUserId = ($match->user1_id === $userId) ? $match->user2_id : $match->user1_id;

            // Diğer kullanıcının profil bilgilerini al
            $otherUserProfile = User::with('profileDetail')->find($otherUserId);

            // Son mesajı al
            $latestMessage = Message::where(function($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $otherUserId);
            })->orWhere(function($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $otherUserId)
                    ->where('receiver_id', $userId);
            })->latest()->first();

            $messages[] = [
                'matched_user_name' => $otherUserProfile->name, // Diğer kullanıcının adı
                'matched_user_profile' => $otherUserProfile->profileDetail, // Diğer kullanıcının profil bilgileri
                'latest_message' => $latestMessage,
                'latest_message_time' => $latestMessage ? $latestMessage->created_at : null, // Son mesaj tarihi
                'other_user_id' => $otherUserId // Diğer kullanıcı ID'si
            ];
        }

        // Mesajları son mesaj tarihine göre sırala
        usort($messages, function ($a, $b) {
            return $b['latest_message_time'] <=> $a['latest_message_time'];
        });

        // Pagination bilgilerini döndür
        return [
            'current_page' => $page,
            'last_page' => (int) ceil(count($messages) / $perPage),
            'per_page' => $perPage,
            'total' => count($messages),
            'data' => array_slice($messages, ($page - 1) * $perPage, $perPage), // Sayfalama işlemi
        ];
    }

    public function sendMessage(int $senderId, int $receiverId, array $data)
    {
        // Gönderici ve alıcı id'lerinin doğruluğunu kontrol et
        $sender = User::find($senderId);
        $receiver = User::find($receiverId);

        if (!$sender || !$receiver) {
            return false;
        }

        // Mesaj oluşturma
         Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'content' => $data['content'],
            'media_path' => $data['media_path'] ?? null, // Media path varsa ekle
        ]);

        return true;
    }

}
