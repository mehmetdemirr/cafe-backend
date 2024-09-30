<?php

namespace App\Interfaces;

interface MatchRepositoryInterface
{
    public function swipeRight(int $userId, int $swipedUserId): bool;

    public function hasMutualRightSwipe(int $userId, int $swipedUserId): bool;

    public function getMatches(int $userId): array;

    public function dismissUser(int $userId, int $dismissedUserId): bool;
}
