<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessEntryRequest;
use App\Http\Requests\BusinessEntryWithLocationRequest;
use App\Interfaces\BusinessEntryRepositoryInterface;
use Illuminate\Http\Request;

class BusinessEntryController extends Controller
{
    protected $businessEntryRepository;

    public function __construct(BusinessEntryRepositoryInterface $businessEntryRepository)
    {
        $this->businessEntryRepository = $businessEntryRepository;
    }

    public function enterCafe(BusinessEntryWithLocationRequest $request)
    {
        $qrCode = $request->input('qr_code');
        $userId = $request->user()->id;

        // Kullanıcı konum bilgileri
        $userLatitude = $request->input('latitude');
        $userLongitude = $request->input('longitude');

        // Giriş yapılabilmesi için maksimum mesafe (kilometre cinsinden)
        $maxDistance = 0.05; // 100 metre mesafe limiti örnek olarak

        // QR kodunu kontrol et
        $business = $this->businessEntryRepository->findByQrCode($qrCode);

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'QR kodu bulunamadı',
                'data' => null,
                'errors' => 'Geçersiz QR kodu'
            ], 404);
        }

        // Kafeye olan mesafeyi hesapla (Haversine formülü)
        $earthRadius = 6371; // Dünya'nın yarıçapı (kilometre cinsinden)
        $distance = $this->calculateDistance($userLatitude, $userLongitude, $business->location_latitude, $business->location_longitude, $earthRadius);

        // Eğer mesafe belirlenen maksimum mesafeden büyükse giriş yapılamaz
        if ($distance > $maxDistance) {
            return response()->json([
                'success' => false,
                'message' => 'Kafeye çok uzaksınız. Giriş yapılamaz.',
                'data' => null,
                'errors' => 'Kafe konumuna çok uzaksınız'
            ], 400);
        }

        // Kullanıcının zaten kafede olup olmadığını kontrol et
        $alreadyInCafe = $this->businessEntryRepository->isUserInCafe($userId, $business->id);

        if ($alreadyInCafe) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı zaten bu kafeye giriş yapmış',
                'data' => null,
                'errors' => 'Zaten giriş yapıldı'
            ], 400);
        }

        // Eğer QR kodu geçerli ve mesafe uygun ise giriş yap
        $success = $this->businessEntryRepository->enterCafe($userId, $qrCode);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Kafeye giriş başarılı',
                'data' => null,
                'errors' => null
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kafeye giriş başarısız',
            'data' => null,
            'errors' => 'Giriş işlemi sırasında bir hata oluştu'
        ], 400);
    }

    // Kullanıcının ve kafenin konum bilgilerini kullanarak mesafeyi hesaplayan fonksiyon
    private function calculateDistance($lat1, $lon1, $lat2, $lon2, $earthRadius)
    {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle; // Mesafe kilometre cinsindendir
    }


    // Kafeden çıkış
    public function leaveCafe(BusinessEntryRequest $request)
    {
        $userId = $request->user()->id;
        $businessId = $request->input('business_id');

        $success = $this->businessEntryRepository->leaveCafe($userId, $businessId);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Kafeden çıkış başarılı',
                'data' => null,
                'errors' => null
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kafeden çıkış başarısız',
            'data' => null,
            'errors' => 'Çıkış yapılamadı.'
        ], 400);
    }

    // Kafedeki kullanıcıları göster
    public function showUsersInCafe(BusinessEntryRequest $request)
    {
        $businessId = $request->input('business_id');

        $users = $this->businessEntryRepository->getUsersInCafe($businessId);

        if (!empty($users)) {
            return response()->json([
                'success' => true,
                'message' => 'Kafedeki kullanıcılar başarıyla getirildi.',
                'data' => $users,
                'errors' => null
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kafede aktif kullanıcı bulunamadı.',
            'data' => null,
            'errors' => 'Kafede şu anda aktif kullanıcı yok.'
        ], 404);
    }

    // Kullanıcının kafede olup olmadığını kontrol et
    public function isUserInCafe(BusinessEntryRequest $request)
    {
        $userId = $request->user()->id;
        $businessId = $request->input('business_id');

        $isInCafe = $this->businessEntryRepository->isUserInCafe($userId, $businessId);

        if ($isInCafe) {
            return response()->json([
                'success' => true,
                'message' => 'Kullanıcı kafede.',
                'data' => true,
                'errors' => null
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kullanıcı kafede değil.',
            'data' => false,
            'errors' => null
        ], 200);
    }
}
