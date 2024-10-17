<?php

namespace App\Http\Controllers\Api;

use App\Helper\OneSignalHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileDetailRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Services\FileService;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $logService;
    protected $userProfileRepository; 
    protected $fileService;

    public function __construct(LogService $logService, UserProfileRepositoryInterface $userProfileRepository,FileService $fileService)
    {
        $this->logService = $logService;
        $this->userProfileRepository = $userProfileRepository;
        $this->fileService = $fileService;
    }

    public function user(Request $request)
    {
        $this->logService->logWarning('Kullanıcı bilgisi');
        // Kullanıcıyı rollerle birlikte yükle
        $user = $request->user()->load('roles');

         // OneSignalHelper::sendToAllUsers('Genel Mesaj', 'https://example.com');
          OneSignalHelper::sendToUser($user->onesignal_id, 'Özel Mesaj', 'https://example.com');

        return response()->json([
            'success'=> true,
            'data' => [
                'user' => $user,
                'roles' => $user->getRoleNames(),
            ],
            'errors' => null,
            'message' => null,
        ], 200);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();

        $user->update($request->only(['name', 'email', 'password']));

        // Şifreyi hash'le
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Kullanıcı bilgileri güncellendi.',
            'errors' => null,
        ], 200);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        // Mevcut parolayı kontrol et
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mevcut parola hatalı.',
                'errors' => null,
                'data' =>false,
            ], status: 400);
        }

        // Yeni parolayı hash'le ve kaydet
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Parola başarıyla değiştirildi.',
            'errors' => null,
            'data' =>true,
        ], 200);
    }

    // Profil detaylarını güncelle
    public function updateProfile(ProfileDetailRequest $request)
    {
        $userId = $request->user()->id;

        // Profil detaylarını güncelle
        $profileData = $request->only(['phone_number', 'biography', 'country', 'city', 'district','gender']);
        
        // Kullanıcının mevcut profil detaylarını al
        $userProfile = $this->userProfileRepository->getProfile($userId);

        // Profil fotoğrafı varsa güncellenecek, önceki fotoğrafı sil ve yeni fotoğrafı ekle
        if ($request->hasFile('profile_picture')) {
            // Eski fotoğraf yolunu al
            $oldFilePath = $userProfile->profile_picture ?? null;
    
            // Yeni fotoğrafı yükle
            $file = $request->file('profile_picture');
            $path = 'profile_pictures'; // Profil fotoğrafları için dosya yolu
    
            // Eski dosyayı sil ve yeni dosyayı yükle
            // Eğer eski dosya yolu mevcutsa, eski dosyayı sil ve yeni dosyayı yükle
            if ($oldFilePath) {
                $updatedFilePath = $this->fileService->update($file, $oldFilePath, $path);
                $profileData['profile_picture'] = $updatedFilePath; // Yeni dosya yolunu ekle
            } else {
                // Eğer eski dosya yolu yoksa, sadece yeni dosyayı yükle
                $profileData['profile_picture'] = $this->fileService->upload($file, $path);
            }
        }

        // Kullanıcı modelinde name güncellemesi
        $user = $request->user();
        if ($request->has('name')) {
            $user->name = $request->input('name');
            $user->save();
        }

        $updated = $this->userProfileRepository->update($userId, $profileData);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Profil detayları başarıyla güncellendi.',
                'data' => null,
                'errors' => null,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Profil detayları güncellenirken bir hata oluştu.',
            'data' => null,
            'errors' => null,
        ], 400);
    }

    // Kullanıcının profil detaylarını getir
    public function getProfile(Request $request)
    {
        $userId = $request->user()->id;

        // Profil detaylarını al
        $profile = $this->userProfileRepository->getProfile($userId);

        if ($profile) {
            return response()->json([
                'success' => true,
                'data' => $profile,
                'message' =>"profile geldi",
                'errors' => null,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Profil detayları bulunamadı.',
            'data' =>null,
            'errors' => null,
        ], 404);
    }

    // Kullanıcının profilini sil
    // public function deleteProfile(Request $request)
    // {
    //     $userId = $request->user()->id;

    //     // Profil detaylarını sil
    //     $deleted = $this->userProfileRepository->delete($userId);

    //     if ($deleted) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Profil detayları başarıyla silindi.',
    //             'data' =>null,
    //             'errors' => null,
    //         ], 200);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Profil detayları silinirken bir hata oluştu.',
    //         'data' =>null,
    //         'errors' => null,
    //     ], 400);
    // }
}
