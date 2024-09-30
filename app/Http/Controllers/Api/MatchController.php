<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatchRequest;
use App\Interfaces\MatchRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MatchController extends Controller
{
    protected $matchRepository;

    public function __construct(MatchRepositoryInterface $matchRepository)
    {
        $this->matchRepository = $matchRepository;
    }

    public function getMatches(Request $request)
    {
        $userId = $request->user()->id;
        $matches = $this->matchRepository->getMatches($userId);

        return response()->json($matches);
    }

    public function swipeRight(MatchRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $swipedUserId = $request->input('swiped_user_id');

        $matched = $this->matchRepository->swipeRight($userId, $swipedUserId);

        return response()->json([
            'data' => $matched, //eşleşiyse true , değilse false dönsün
            'errors' => null,
            'message' => $matched ? 'Eşleşme gerçekleşti!' : 'Sağa kaydırma kaydedildi.',
            'success' => true,
        ]);
    }

    public function dismissUser(MatchRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $dismissedUserId = $request->input('swiped_user_id');

        $this->matchRepository->dismissUser($userId, $dismissedUserId);

        return response()->json([
            'data' => null,
            'errors' => null,
            'message' => 'Kullanıcı başarıyla göz ardı edildi.',
            'success' => true,
        ]);
    }
}
