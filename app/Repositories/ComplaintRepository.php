<?php

namespace App\Repositories;
use App\Interfaces\ComplaintRepositoryInterface;
use App\Models\Complaint;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function reportUser(array $data): bool
    {
        try {
            Complaint::create($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
