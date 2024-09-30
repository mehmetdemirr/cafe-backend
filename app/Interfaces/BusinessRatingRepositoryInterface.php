<?php

namespace App\Interfaces;

use App\Models\BusinessRating;

interface BusinessRatingRepositoryInterface
{
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getAllByBusinessId($businessId);
    public function getAverageRating($businessId);
    public function exists($userId, $businessId);
    public function find($id);
}
