<?php

namespace App\Interfaces;

interface CampaignInterface
{
    public function createCampaign(array $data): bool;
    public function updateCampaign(int $campaignId, array $data): bool;
    public function deleteCampaign(int $campaignId): bool;

    public function joinCampaign(int $campaignId, int $userId): bool;
    public function leaveCampaign(int $campaignId, int $userId): bool;

    public function isUserJoinedToCampaign(int $campaignId, int $userId): bool;

    public function getUserCampaigns(int $userId): array;
    public function getActiveCampaigns(): array;
    public function endExpiredCampaigns(): void;
}
