<?php

namespace App\Repositories;
use App\Interfaces\MessageRepositoryInterface;
use App\Models\Matchup;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

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
            ->with(['sender.profileDetail', 'receiver.profileDetail'])
            ->orderBy('created_at', 'asc')
            ->forPage($page, $perPage) // Sayfalama için
            ->paginate($perPage);
    }
    public function getConversationsByUserId(int $userId, int $page = 1, int $perPage = 10)
    {

        // Kullanıcının eşleşmelerini bul
        // $matchedUserIds = Matchup::where('user1_id', $userId)
        // ->orWhere('user2_id', $userId)
        // ->pluck('user1_id', 'user2_id') // Bu kısım hatalı
        // ->merge(Matchup::where('user2_id', $userId)->pluck('user1_id'))
        // ->unique();

        $matchedUserIds = Matchup::all();

            

        // Eğer eşleşme yoksa boş bir sonuç döndür
        if ($matchedUserIds->isEmpty()) {
            return collect(); // veya bir hata mesajı dönebilirsiniz
        }

        // Mesajları getir
        $messages = Message::where(function ($query) use ($userId, $matchedUserIds) {
            $query->where('sender_id', $userId)
                ->whereIn('receiver_id', $matchedUserIds);
        })
        ->orWhere(function ($query) use ($userId, $matchedUserIds) {
            $query->where('receiver_id', $userId)
                ->whereIn('sender_id', $matchedUserIds);
        })
        ->with(['sender.profileDetail', 'receiver.profileDetail'])
        ->orderBy('created_at', 'desc')
        ->get(); // Tüm mesajları al

        // Eşleşmeleri ile birlikte mesajları formatlayın
        return $matchedUserIds->map(function ($matchedUserId) use ($messages, $userId) {
            // Mesajları ilgili eşleşme ile eşleştir
            $message = $messages->first(function ($msg) use ($matchedUserId, $userId) {
                return $msg->sender_id === $userId && $msg->receiver_id === $matchedUserId ||
                    $msg->receiver_id === $userId && $msg->sender_id === $matchedUserId;
            });

            // Kullanıcı bilgilerini ve son mesajı formatlayın
            return [
                'user' => [
                    'id' => $matchedUserId,
                    'name' => $message ? ($message->sender_id === $userId ? $message->receiver->name : $message->sender->name) : null,
                    'profile_picture' => $message ? ($message->sender_id === $userId ? $message->receiver->profileDetail->profile_picture : $message->sender->profileDetail->profile_picture) : null,
                ],
                'last_message' => $message ? $message->content : null,
                'last_message_time' => $message ? $message->created_at->toDateTimeString() : null,
                'has_messages' => $message !== null, // Mesaj var mı kontrolü
            ];
        })->values(); // Sonuçları döndür
    }

}
