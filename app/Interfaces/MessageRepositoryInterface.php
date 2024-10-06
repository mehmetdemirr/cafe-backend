<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface MessageRepositoryInterface
{
    public function getConversationWithUser(int $userId, int $otherUserId, int $page = 1, int $perPage = 10);
    public function getConversationsByUserId(int $userId, int $page = 1, int $perPage = 10);
}
