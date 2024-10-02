<?php

namespace App\Interfaces;

interface MatchRepositoryInterface
{
    public function swipeRight(int $userId, int $swipedUserId): bool;
    public function hasMutualRightSwipe(int $userId, int $swipedUserId): bool;
    public function getMatches(int $userId): array;
    public function dismissUser(int $userId, int $dismissedUserId): bool;
    public function potentialMatches(int $userId): array;
    public function getLeftSwipedUsers(int $userId);
    public function getRightSwipedUsers(int $userId);
    public function hasSwiped(int $userId, int $swipedUserId): ?bool;
}
