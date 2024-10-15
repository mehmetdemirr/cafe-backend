<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessEntryRequest;
use App\Interfaces\BusinessEntryRepositoryInterface;
use Illuminate\Http\Request;

class BusinessEntryController extends Controller
{
    protected $businessEntryRepository;

    public function __construct(BusinessEntryRepositoryInterface $businessEntryRepository)
    {
        $this->businessEntryRepository = $businessEntryRepository;
    }

    public function enterCafe(Request $request)
    {
        $qrCode = $request->input('qr_code');
        $userId = $request->user()->id;

        // İlk olarak QR kodunu kontrol et
        $business = $this->businessEntryRepository->findByQrCode($qrCode);

        if (!$business) {
            return response()->json([
                'success' => false,
                'message' => 'QR kodu bulunamadı',
                'data' => null,
                'errors' => 'Geçersiz QR kodu'
            ], 404); // QR kodu bulunamadı hatası
        }

        // Kullanıcının zaten kafede olup olmadığını kontrol et
        $alreadyInCafe = $this->businessEntryRepository->isUserInCafe($userId, $business->id);

        if ($alreadyInCafe) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı zaten bu kafeye giriş yapmış',
                'data' => null,
                'errors' => 'Zaten giriş yapıldı'
            ], 400); // Kullanıcı zaten giriş yapmış
        }

        // Eğer QR kodu geçerli ve kullanıcı daha önce giriş yapmadıysa giriş yap
        $success = $this->businessEntryRepository->enterCafe($userId, $qrCode);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Kafeye giriş başarılı',
                'data' => null,
                'errors' => null
            ], 200);
        }

        // Diğer durumlar için genel hata mesajı
        return response()->json([
            'success' => false,
            'message' => 'Kafeye giriş başarısız',
            'data' => null,
            'errors' => 'Giriş işlemi sırasında bir hata oluştu'
        ], 400);
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
