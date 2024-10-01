<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignRequest;
use App\Interfaces\CampaignInterface;
use Illuminate\Http\JsonResponse;

class CampaignController extends Controller
{
    private $campaignRepository;

    public function __construct(CampaignInterface $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    public function create(CampaignRequest $request): JsonResponse
    {
        $result = $this->campaignRepository->createCampaign($request->validated());

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Campaign created successfully.' : 'Failed to create campaign.',
            'data' => $result ? $result : null,
            'errors' => !$result ? ['error' => 'An error occurred while creating the campaign.'] : null,
        ]);
    }

    public function update(CampaignRequest $request, int $campaignId): JsonResponse
    {
        $result = $this->campaignRepository->updateCampaign($campaignId, $request->validated());

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Campaign updated successfully.' : 'Failed to update campaign.',
            'data' => null,
            'errors' => !$result ? ['error' => 'An error occurred while updating the campaign.'] : null,
        ]);
    }

    public function delete(int $campaignId): JsonResponse
    {
        $result = $this->campaignRepository->deleteCampaign($campaignId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Campaign deleted successfully.' : 'Failed to delete campaign.',
            'data' => null,
            'errors' => !$result ? ['error' => 'An error occurred while deleting the campaign.'] : null,
        ]);
    }

    public function joinCampaign(int $campaignId, int $userId): JsonResponse
    {
        $result = $this->campaignRepository->joinCampaign($campaignId, $userId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Joined the campaign successfully.' : 'Failed to join the campaign.',
            'data' => null,
            'errors' => !$result ? ['error' => 'An error occurred while joining the campaign.'] : null,
        ]);
    }

    public function leaveCampaign(int $campaignId, int $userId): JsonResponse
    {
        $result = $this->campaignRepository->leaveCampaign($campaignId, $userId);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Left the campaign successfully.' : 'Failed to leave the campaign.',
            'data' => null,
            'errors' => !$result ? ['error' => 'An error occurred while leaving the campaign.'] : null,
        ]);
    }

    public function isUserJoined(int $campaignId, int $userId): JsonResponse
    {
        $isJoined = $this->campaignRepository->isUserJoinedToCampaign($campaignId, $userId);

        return response()->json([
            'success' => true,
            'message' => 'User join status retrieved successfully.',
            'data' => ['isJoined' => $isJoined],
            'errors' => null,
        ]);
    }

    public function getActiveCampaigns(int $businessId): JsonResponse
    {
        $campaigns = $this->campaignRepository->getActiveCampaignsByBusinessId($businessId);

        return response()->json([
            'success' => true,
            'message' => 'Active campaigns retrieved successfully.',
            'data' => $campaigns,
            'errors' => null,
        ]);
    }
}
