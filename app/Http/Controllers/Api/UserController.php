<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileDetailRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $logService;
    protected $userProfileRepository; // UserProfileRepository'yi tanımla

    public function __construct(LogService $logService, UserProfileRepositoryInterface $userProfileRepository)
    {
        $this->logService = $logService;
        $this->userProfileRepository = $userProfileRepository; // Repository'yi initialize et
    }

    public function user(Request $request)
    {
        $this->logService->logWarning('Kullanıcı bilgisi');
        // Kullanıcıyı rollerle birlikte yükle
        $user = $request->user()->load('roles');

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
            // Önceki fotoğraf varsa sil
            if ($userProfile && $userProfile->profile_picture) {
                // Storage'den sil
                Storage::disk('public')->delete($userProfile->profile_picture);
            }

            // Yeni fotoğrafı kaydet
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');

            // Dosya yolunu profildeki verilere ekle
            $profileData['profile_picture'] = $filePath;
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
