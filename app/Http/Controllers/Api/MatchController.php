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

        return response()->json([
            'data' => $matches,
            'errors' => null,
            'message' =>null,
            'success' => true,
        ]);
    }

    public function swipeRight(MatchRequest $request): JsonResponse
    {
        // TODO burda favori business üyesi olup olmadığını kontrol et açık olmasın
        $userId = $request->user()->id;
        $swipedUserId = $request->input('swiped_user_id');

        // Kendine kaydırma yapılıp yapılmadığını kontrol et
        if ($userId === $swipedUserId) {
            return response()->json([
                'data' => false,
                'errors' => null,
                'message' => 'Kendine kaydıramazsın.',
                'success' => false,
            ]);
        }

        // Kullanıcının daha önce kaydırıp kaydırmadığını kontrol et
        $hasSwiped = $this->matchRepository->hasSwiped($userId, $swipedUserId);

        if ($hasSwiped) {
            return response()->json([
                'data' => null,
                'errors' => null,
                'message' => 'Bu kullanıcıyı daha önce kaydırmışsınız.',
                'success' => false,
            ]);
        }

        // Eşleşme olup olmadığını kontrol et
        $matched = $this->matchRepository->swipeRight($userId, $swipedUserId);

        // Sonuçlara göre farklı mesajlar döndür
        if ($matched) {
            return response()->json([
                'data' => true,
                'errors' => null,
                'message' => 'Eşleşme gerçekleşti!',
                'success' => true,
            ]);
        } else {
            return response()->json([
                'data' => false,
                'errors' => null,
                'message' => 'Sağa kaydırma kaydedildi.',
                'success' => true,
            ]);
        }
    }

    public function dismissUser(MatchRequest $request): JsonResponse
    {
        // TODO burda favori business üyesi olup olmadığını kontrol et açık olmasın
        $userId = $request->user()->id;
        $dismissedUserId = $request->input('swiped_user_id');

        // Kendine kaydırma yapılıp yapılmadığını kontrol et
        if ($userId === $dismissedUserId) {
            return response()->json([
                'data' => false,
                'errors' => null,
                'message' => 'Kendine kaydıramazsın.',
                'success' => false,
            ]);
        }

        // Kullanıcının daha önce kaydırıp kaydırmadığını kontrol et
        $hasSwiped = $this->matchRepository->hasSwiped($userId, $dismissedUserId);

        if ($hasSwiped) {
            return response()->json([
                'data' => null,
                'errors' => null,
                'message' => 'Bu kullanıcıyı daha önce kaydırmışsınız.',
                'success' => false,
            ]);
        }

        // Kullanıcıyı göz ardı et
        $result = $this->matchRepository->dismissUser($userId, $dismissedUserId);

        // Kullanıcı başarıyla göz ardı edildiğini kontrol et
        if ($result) {
            return response()->json([
                'data' => null,
                'errors' => null,
                'message' => 'Kullanıcı başarıyla göz ardı edildi.',
                'success' => true,
            ]);
        } else {
            return response()->json([
                'data' => null,
                'errors' => null,
                'message' => 'Bir hata oluştu, kullanıcıyı göz ardı edemedik.',
                'success' => false,
            ]);
        }
    }

    /**
     * Potansiyel eşleşmeleri getir.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getPotentialMatches(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $potentialMatches = $this->matchRepository->potentialMatches($userId);

        return response()->json([
            'success' => true,
            'data' => $potentialMatches,
            'message' => 'Potansiyel eşleşme olacak kişiler başarıyla getirildi.',
            'errors' => null,
        ]);
    }

    public function getRightSwipedUsers(Request $request)
    {
        $userId = $request->user()->id;
        $rightSwipedUsers = $this->matchRepository->getRightSwipedUsers($userId);

        return response()->json([
            'success' => true,
            'data' => $rightSwipedUsers,
            'message' => 'Sağa kaydırılan kişiler kişiler başarıyla getirildi.',
            'errors' => null,
        ]);
    }

    public function getLeftSwipedUsers(Request $request)
    {
        $userId = $request->user()->id;
        $leftSwipedUsers = $this->matchRepository->getLeftSwipedUsers($userId);

        return response()->json([
            'success' => true,
            'data' => $leftSwipedUsers,
            'message' => 'Sola kaydırılan kişiler kişiler başarıyla getirildi.',
            'errors' => null,
        ]);
    }
}
