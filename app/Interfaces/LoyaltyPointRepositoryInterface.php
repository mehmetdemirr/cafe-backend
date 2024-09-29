<?php

namespace App\Interfaces;

interface LoyaltyPointRepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?array;
    public function create(array $data): array;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function findByUserId(int $userId): array;
}
