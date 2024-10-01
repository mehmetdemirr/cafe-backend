<?php

namespace App\Repositories;

use App\Interfaces\SupportMessageRepositoryInterface;
use App\Models\SupportMessage;
use Illuminate\Database\Eloquent\Collection;

class SupportMessageRepository implements SupportMessageRepositoryInterface
{
    public function getAllByUserId(int $userId): Collection
    {
        return SupportMessage::where('user_id', $userId)->get();
    }

    public function create(array $data): SupportMessage
    {
        return SupportMessage::create($data);
    }

    public function delete(int $id): bool
    {
        return SupportMessage::destroy($id) > 0;
    }
}
