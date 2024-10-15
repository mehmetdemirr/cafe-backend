<?php

namespace App\Repositories;

use App\Interfaces\BusinessEntryRepositoryInterface;
use App\Models\Business;
use App\Models\UserBusinessEntry;
use Carbon\Carbon;

class BusinessEntryRepository implements BusinessEntryRepositoryInterface
{

    // QR koduna göre işletmeyi bulma
    public function findByQrCode(string $qrCode): ?Business
    {
        return Business::where('qr_code', $qrCode)->first();
    }
    
    public function enterCafe(int $userId, string $qrCode): bool
    {
        // Kafeyi QR kod ile bul
        $business = Business::where('qr_code', $qrCode)->first();

        if (!$business) {
            return false;
        }

        // Kullanıcının aynı kafeye daha önce giriş yapıp yapmadığını kontrol et
        if ($this->isUserInCafe($userId, $business->id)) {
            return false;
        }

        // Giriş kaydını oluştur
        UserBusinessEntry::create([
            'user_id' => $userId,
            'business_id' => $business->id,
            'entry_time' => Carbon::now(),
        ]);

        return true;
    }

    public function leaveCafe(int $userId, int $businessId): bool
    {
        // Kullanıcının aktif bir giriş kaydı olup olmadığını kontrol et
        $entry = UserBusinessEntry::where('user_id', $userId)
            ->where('business_id', $businessId)
            ->whereNull('exit_time')
            ->first();

        if (!$entry) {
            return false;
        }

        // Çıkış zamanını güncelle
        $entry->update([
            'exit_time' => Carbon::now(),
        ]);

        return true;
    }

    public function isUserInCafe(int $userId, int $businessId): bool
    {
        return UserBusinessEntry::where('user_id', $userId)
            ->where('business_id', $businessId)
            ->whereNull('exit_time')
            ->exists();
    }

    public function getUsersInCafe(int $businessId): array
    {
        // Kafede aktif olan kullanıcıların bilgilerini getir
        $users = UserBusinessEntry::where('business_id', $businessId)
            ->whereNull('exit_time') // Çıkış yapmamış olanlar
            ->with(['user' => function ($query) {
                // Kullanıcının id, ad ve profil resmini seç
                $query->select('id', 'name')
                    ->with(['profileDetail' => function ($query) {
                        $query->select('user_id', 'profile_picture'); // Profil resmini getir
                    }]);
            }])
            ->get();

        return $users->toArray();
    }
}
