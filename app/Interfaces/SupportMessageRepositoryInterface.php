<?php

namespace App\Interfaces;

use App\Models\SupportMessage;
use Illuminate\Database\Eloquent\Collection;

interface SupportMessageRepositoryInterface
{
    public function getAllByUserId(int $userId): Collection;

    public function create(array $data): SupportMessage;

    public function delete(int $id): bool;
}
