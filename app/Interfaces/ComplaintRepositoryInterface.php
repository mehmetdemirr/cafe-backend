<?php

namespace App\Interfaces;

interface ComplaintRepositoryInterface
{
    public function reportUser(array $data): bool;
}
