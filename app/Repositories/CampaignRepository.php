<?php

namespace App\Repositories;
use App\Interfaces\CampaignInterface;
use App\Models\Campaign;
use App\Models\User;
use Carbon\Carbon;

class CampaignRepository implements CampaignInterface
{
    public function createCampaign(array $data): bool
    {
        return Campaign::create($data) ? true : false;
    }

    public function updateCampaign(int $campaignId, array $data): bool
    {
        $campaign = Campaign::find($campaignId);
        if ($campaign) {
            return $campaign->update($data);
        }
        return false;
    }

    public function deleteCampaign(int $campaignId): bool
    {
        $campaign = Campaign::find($campaignId);
        if ($campaign) {
            return $campaign->delete();
        }
        return false;
    }

    public function joinCampaign(int $campaignId, int $userId): bool
    {
        $campaign = Campaign::find($campaignId);
        $user = User::find($userId);

        if ($campaign && $user && $this->isCampaignActive($campaign)) {
            $user->campaigns()->attach($campaignId);
            return true;
        }

        return false;
    }

    public function leaveCampaign(int $campaignId, int $userId): bool
    {
        $campaign = Campaign::find($campaignId);
        $user = User::find($userId);

        if ($campaign && $user) {
            $user->campaigns()->detach($campaignId);
            return true;
        }

        return false;
    }

    public function isUserJoinedToCampaign(int $campaignId, int $userId): bool
    {
        return Campaign::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('id', $campaignId)->exists();
    }

    public function getUserCampaigns(int $userId): array
    {
        return User::find($userId)->campaigns->toArray();
    }

    public function getActiveCampaigns(): array
    {
        return Campaign::where('end_date', '>', Carbon::now())->get()->toArray();
    }

    public function endExpiredCampaigns(): void
    {
        Campaign::where('end_date', '<', Carbon::now())->each(function ($campaign) {
            $campaign->users()->detach();  // Kullanıcıların kampanya katılımlarını sonlandır
        });
    }

    private function isCampaignActive(Campaign $campaign): bool
    {
        $currentDate = Carbon::now();
        return $currentDate->between($campaign->start_date, $campaign->end_date);
    }

    public function getActiveCampaignsByBusinessId(int $businessId): array
    {
        return Campaign::where('business_id', $businessId)
                    ->where('end_date', '>', Carbon::now())
                    ->get()
                    ->toArray();
    }
}
