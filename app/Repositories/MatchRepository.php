<?php

namespace App\Repositories;
use App\Interfaces\MatchRepositoryInterface;
use App\Models\Business;
use App\Models\Matchup;
use App\Models\Swipe;
use App\Models\User;

class MatchRepository implements MatchRepositoryInterface
{
    public function swipeRight(int $userId, int $swipedUserId): bool
    {
        // Kendine kaydırma kontrolü
        if ($userId === $swipedUserId) {
            return false; // Kendine kaydırma yapamaz
        }

        // Sola kaydırma kaydı var mı kontrol et
        $leftSwipeExists = Swipe::where('user_id', $userId)
                                ->where('swiped_user_id', $swipedUserId)
                                ->where('is_right', false)
                                ->exists();

        // Eğer sola kaydırma varsa, önce onu sil
        if ($leftSwipeExists) {
            Swipe::where('user_id', $userId)
                ->where('swiped_user_id', $swipedUserId)
                ->delete();
        }

        // Check if a swipe exists for the reverse case
        $reverseSwipe = Swipe::where('user_id', $swipedUserId)
                            ->where('swiped_user_id', $userId)
                            ->where('is_right', true)
                            ->first();

        // If reverse swipe exists, create a match
        if ($reverseSwipe) {
            Matchup::create([
                'user1_id' => $userId,
                'user2_id' => $swipedUserId,
            ]);
            // Otherwise, just record the swipe
            Swipe::create([
                'user_id' => $userId,
                'swiped_user_id' => $swipedUserId,
                'is_right' => true,
                'is_matched' => true,
            ]);
            return true; // Matched
        }
        else
        {
            Swipe::create([
                'user_id' => $userId,
                'swiped_user_id' => $swipedUserId,
                'is_right' => true,
                'is_matched' => false,
            ]);
        }
        return false; // Not yet matched
    }

    public function dismissUser(int $userId, int $dismissedUserId): bool
    {
        // Kendine kaydırma kontrolü
        if ($userId === $dismissedUserId) {
            return false; // Kendine kaydırma yapamaz
        }

        // Sağ kaydırma kaydı var mı kontrol et
        $rightSwipeExists = Swipe::where('user_id', $userId)
                                ->where('swiped_user_id', $dismissedUserId)
                                ->where('is_right', true)
                                ->exists();

        // Eğer sağa kaydırma varsa, önce onu sil
        if ($rightSwipeExists) {
            Swipe::where('user_id', $userId)
                ->where('swiped_user_id', $dismissedUserId)
                ->delete();
        }

        // Kullanıcıyı göz ardı et
        Swipe::create([
            'user_id' => $userId,
            'swiped_user_id' => $dismissedUserId,
            'is_right' => false,
        ]);

        return true;
    }

    public function hasMutualRightSwipe(int $userId, int $swipedUserId): bool
    {
        return Matchup::where(function ($query) use ($userId, $swipedUserId) {
            $query->where('user1_id', $userId)
                  ->where('user2_id', $swipedUserId);
        })->orWhere(function ($query) use ($userId, $swipedUserId) {
            $query->where('user1_id', $swipedUserId)
                  ->where('user2_id', $userId);
        })->exists();
    }

    public function getMatches(int $userId): array
    {
        $matches =  Matchup::where('user1_id', $userId)
        ->orWhere('user2_id', $userId)
        ->with(['user1', 'user2']) // Kullanıcı bilgilerini ekle
        ->get()->toArray();

        return $matches;
    }

    public function potentialMatches(int $userId): array
    {
        // Kullanıcının bilgilerini al
        $user = User::find($userId);

        // Kullanıcının favori işletmelerinin ID'lerini al
        $favoriteBusinessIds = $user->favoriteBusinesses()->pluck('business_id')->toArray();

        // Favori işletmelerin kullanıcılarını al
        $usersWithFavoriteBusinesses = Business::whereIn('id', $favoriteBusinessIds)
            ->with('favoriteByUsers') // Favori işletmeyi beğenen kullanıcıları al
            ->get()
            ->pluck('favoriteByUsers.*.id') // Favori kullanıcıların ID'lerini al
            ->flatten()
            ->unique()
            ->toArray();

        // Kullanıcının swipe ettiği kullanıcıların ID'lerini al
        $swipedUserIds = Swipe::where('user_id', $userId)
            ->pluck('swiped_user_id')
            ->toArray();

        // Kullanıcının eşleştiği kullanıcıların ID'lerini al
        $matchedUserIdsFromMatchups = Matchup::where('user1_id', $userId)
            ->orWhere('user2_id', $userId)
            ->pluck('user1_id')
            ->merge(Matchup::where('user2_id', $userId)->pluck('user2_id'))
            ->toArray();

        // Kullanıcının hem swiped (kaydırdığı) hem de matched (eşleştiği) kullanıcılar ile kendisini hariç tutarak diğer kullanıcıları al
        $matches = User::whereIn('id', array_merge($usersWithFavoriteBusinesses))
            ->whereNotIn('id', array_merge($swipedUserIds, $matchedUserIdsFromMatchups, [$userId])) // Kendini de hariç tut
            // ->with('favoriteBusinesses') // Her kullanıcı için favori işletmelerini getir
            ->distinct() // Her kullanıcının yalnızca bir kez görünmesini sağla
            ->get();

        return $matches->toArray();
    }

    //sağa kaydırdığı kullanıcıları alma
    public function getRightSwipedUsers(int $userId): array
    {
        // Kullanıcının sağa kaydırdığı kullanıcıları al ve User modelini döndür
        return User::whereIn('id', function($query) use ($userId) {
            $query->select('swiped_user_id')
                ->from('swipes')
                ->where('user_id', $userId)
                ->where('is_right', true);
        })->get()->toArray();
    }

     //sola kaydırdığı kullanıcıları alma
    public function getLeftSwipedUsers(int $userId): array
    {
        // Kullanıcının sola kaydırdığı kullanıcıları al ve User modelini döndür
        return User::whereIn('id', function($query) use ($userId) {
            $query->select('swiped_user_id')
                ->from('swipes')
                ->where('user_id', $userId)
                ->where('is_right', false);
        })->get()->toArray();
    }

    // Kullanıcının kaydırma işlemini kontrol etme
    public function hasSwiped(int $userId, int $swipedUserId): ?bool
    {
        return Swipe::where('user_id', $userId)
                    ->where('swiped_user_id', $swipedUserId)
                    ->exists();
    }

}
