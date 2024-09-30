<?php

namespace App\Repositories;
use App\Interfaces\BusinessRatingRepositoryInterface;
use App\Models\BusinessRating;

class BusinessRatingRepository implements BusinessRatingRepositoryInterface
{
    public function create(array $data)
    {
        return BusinessRating::create($data);
    }

    public function update($id, array $data)
    {
        $rating = BusinessRating::findOrFail($id);
        $rating->update($data);
        return $rating;
    }

    public function delete($id)
    {
        BusinessRating::destroy($id);
    }

    public function getAllByBusinessId($businessId)
    {
        return BusinessRating::where('business_id', $businessId)->get();
    }

    public function getAverageRating($businessId)
    {
        return BusinessRating::where('business_id', $businessId)->average('rating');
    }

    public function exists($userId, $businessId)
    {
        return BusinessRating::where('user_id', $userId)
            ->where('business_id', $businessId)
            ->exists();
    }

    public function find($id)
    {
        return BusinessRating::find($id);
    }
}
