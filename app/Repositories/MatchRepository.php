<?php

namespace App\Repositories;
use App\Interfaces\MatchRepositoryInterface;
use App\Models\Matchup;
use App\Models\Swipe;

class MatchRepository implements MatchRepositoryInterface
{
    public function swipeRight(int $userId, int $swipedUserId): bool
    {
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
            return true; // Matched
        }

        // Otherwise, just record the swipe
        Swipe::create([
            'user_id' => $userId,
            'swiped_user_id' => $swipedUserId,
            'is_right' => true,
        ]);

        return false; // Not yet matched
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
        $matches = Matchup::where('user1_id', $userId)
                        ->orWhere('user2_id', $userId)
                        ->get();

        return $matches->toArray();
    }

    public function dismissUser(int $userId, int $dismissedUserId): bool
    {
        Swipe::create([
            'user_id' => $userId,
            'swiped_user_id' => $dismissedUserId,
            'is_right' => false,
        ]);
        return true;
    }
}
