<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BusinessRatingRequest;
use App\Http\Requests\UpdateBusinessRatingRequest;
use App\Interfaces\BusinessRatingRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BusinessRatingController extends Controller
{
    protected $businessRatingRepository;

    public function __construct(BusinessRatingRepositoryInterface $businessRatingRepository)
    {
        $this->businessRatingRepository = $businessRatingRepository;
    }

    public function store(BusinessRatingRequest $request): JsonResponse
    {
        // Kullanıcının belirli bir işyerine daha önce bir değerlendirme yapıp yapmadığını kontrol et
        if ($this->businessRatingRepository->exists($request->user_id, $request->business_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işyeri için zaten bir değerlendirme yaptınız.',
                'errors' => null,
                'data' =>null,
            ], 400); 
        }

        // Değerlendirmeyi oluştur
        $rating = $this->businessRatingRepository->create($request->validated());
        
        return response()->json([
            'success' => true,
            'data' => $rating,
            'message' => 'Değerlendirme başarıyla oluşturuldu.',
            'errors' => ""
        ], 201);
    }

    /**
     * Belirli bir değerlendirmeyi güncelle.
     */
    public function update(UpdateBusinessRatingRequest $request, $id): JsonResponse
    {
        // Önce modelin var olup olmadığını kontrol et
        $rating = $this->businessRatingRepository->find($id);
    
        if (!$rating) {
            return response()->json([
                'success' => false,
                'message' => 'Güncellenecek değerlendirme bulunamadı.',
                'errors' => 'Bu ID ile bir değerlendirme mevcut değil.',
                'data' => null,
            ], 404);
        }
    
        // Güncelleme işlemi
        $updatedRating = $this->businessRatingRepository->update($id, $request->validated());
    
        return response()->json([
            'success' => true,
            'data' => $updatedRating,
            'message' => 'Değerlendirme başarıyla güncellendi.',
            'errors' => null
        ]);
    }

    /**
     * Belirli bir değerlendirmeyi sil.
     */
    public function destroy($id): JsonResponse
    {
        $deleted = $this->businessRatingRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Değerlendirme başarıyla silindi.',
                'errors' => null,
                'data' => null,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Değerlendirme bulunamadı.',
                'errors' => 'Silinecek değerlendirme mevcut değil.',
                'data' => null,
            ], 404);
        }
    }

    /**
     * Belirli bir işyerinin tüm değerlendirmelerini al.
     */
    public function getAllByBusinessId($businessId): JsonResponse
    {
        $ratings = $this->businessRatingRepository->getAllByBusinessId($businessId);
        
        return response()->json([
            'success' => true,
            'data' => $ratings,
            'message' => 'Değerlendirmeler başarıyla alındı.',
            'errors' => null
        ]);
    }

    /**
     * Belirli bir işyerinin ortalama değerlendirmesini al.
     */
    public function getAverageRating($businessId): JsonResponse
    {
        $average = $this->businessRatingRepository->getAverageRating($businessId);
        
        return response()->json([
            'success' => true,
            'data' => ['average_rating' => $average],
            'message' => 'Ortalama değerlendirme başarıyla alındı.',
            'errors' => null
        ]);
    }
}
