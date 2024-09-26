<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\BusinessRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\BusinessCreateRequest;
use App\Http\Requests\BusinessUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    protected $businessRepository;

    public function __construct(BusinessRepositoryInterface $businessRepository)
    {
        $this->businessRepository = $businessRepository;
    }

    public function store(BusinessCreateRequest $request): JsonResponse
    {
        $userId = $request->user()->id;

        // Kullanıcının zaten bir kafesi var mı kontrol et
        if ($this->businessRepository->exists(['user_id' => $userId])) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Bir kullanıcı yalnızca bir tane kafe oluşturabilir.',
                'errors' => 'Kullanıcı zaten bir kafeye sahip.',
            ], 400);
        }

        $validatedData = $request->validated();
        $validatedData['user_id'] = $userId;

        $business = $this->businessRepository->create($validatedData);
        return response()->json([
            'success' => true,
            'data' => $business,
            'message' => 'İşletme başarıyla oluşturuldu.',
            'errors' => null,
        ], 200);
    }

    public function update(BusinessUpdateRequest $request, $id): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = $request->user()->id;
        $updated = $this->businessRepository->update($id, $validatedData);
        if ($updated) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'İşletme başarıyla güncellendi.',
                'errors' => null,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'data' =>null,
            'message' => 'İşletme bulunamadı veya güncellenemedi.',
            'errors' => null,
        ], 404);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->businessRepository->delete($id);
        if ($deleted) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'İşletme başarıyla silindi.',
                'errors' => null,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'İşletme silinemedi.',
            'errors' => null,
        ], 404);
    }

    public function show($id): JsonResponse
    {
        $business = $this->businessRepository->find($id);
        if ($business) {
            return response()->json([
                'success' => true,
                'data' => $business,
                'message' => 'İşletme bilgileri alındı.',
                'errors' => null,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'İşletme bulunamadı.',
            'errors' => null,
        ], 404);
    }

    public function index(): JsonResponse
    {
        $businesses = $this->businessRepository->all();
        return response()->json([
            'success' => true,
            'data' => $businesses,
            'message' => 'İşletmeler listelendi.',
            'errors' => null,
        ], 200);
    }

    public function favoriteBusinesses(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $favorites = $this->businessRepository->getFavoriteBusinesses($userId);
        return response()->json([
            'success' => true,
            'data' => $favorites,
            'message' => 'Favori işletmeler listelendi.',
            'errors' => null,
        ], 200);
    }

    public function addToFavorites($id): JsonResponse
    {
        $userId = Auth::id();
        // İşletmenin var olup olmadığını kontrol et
        if (!$this->businessRepository->exists(['id' => $id])) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Favori eklemek için geçersiz işletme ID.',
                'errors' => null,
            ], 404);
        }

        // Kullanıcının zaten favorilerine eklemiş olup olmadığını kontrol et
        if ($this->businessRepository->isFavorited($id, $userId)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Bu işletme zaten favorilerinizde.',
                'errors' => null,
            ], 400);
        }

        // Favorilere ekleme işlemini gerçekleştirin
        $added = $this->businessRepository->addToFavorites($id, $userId);
        
        return $added ? response()->json([
            'success' => true,
            'data' => null,
            'message' => 'İşletme favorilere eklendi.',
            'errors' => null,
        ], 200) : response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Favorilere eklenemedi.',
            'errors' => null,
        ], 404);
    }
    
    public function removeFromFavorites($id): JsonResponse
    {
        $userId = Auth::id();
        $removed = $this->businessRepository->removeFromFavorites($id, $userId);
        return $removed ? response()->json([
            'success' => true,
            'data' => null,
            'message' => 'İşletme favorilerden çıkarıldı.',
            'errors' => null,
        ], 200) : response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Favorilerden çıkarılamadı.',
            'errors' => null,
        ], 404);
    }

    public function rate($id, Request $request): JsonResponse
    {
        $userId = Auth::id();
        $rating = $request->input('rating');
        $rated = $this->businessRepository->rateBusiness($id, $userId, $rating);
        return $rated ? response()->json([
            'success' => true,
            'data' => null,
            'message' => 'İşletme puanlandı.',
            'errors' => null,
        ], 200) : response()->json([
            'success' => false,
            'data' => null,
            'message' => 'İşletme puanlanamadı.',
            'errors' => null,
        ], 404);
    }

    public function ratings($id): JsonResponse
    {
        $ratings = $this->businessRepository->getBusinessRatings($id);
        return response()->json([
            'success' => true,
            'data' => $ratings,
            'message' => 'İşletme puanları listelendi.',
            'errors' => null,
        ], 200);
    }
}
