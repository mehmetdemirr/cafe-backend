<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\UserBusinessEntry;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoExitUsersCommand extends Command
{
    protected $signature = 'business:auto-exit-users';
    protected $description = 'Kapanış saatine göre kullanıcılara otomatik çıkış yaptırır';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Şu anki zaman
        $now = Carbon::now();

        // İşletmeleri getir
        $businesses = Business::where('closing_time', '<=', $now->toTimeString())->get();

        foreach ($businesses as $business) {
            // Kapanış saatinden sonra hala kafede olan kullanıcıları bul
            $activeUsers = UserBusinessEntry::where('business_id', $business->id)
                ->whereNull('exit_time') // Çıkış yapmamış kullanıcılar
                ->get();

            // Kullanıcıların çıkış zamanını güncelle
            foreach ($activeUsers as $entry) {
                $entry->update([
                    'exit_time' => Carbon::now(),
                ]);

                // Log veya başka bir işlem yapabilirsiniz
                $this->info("Kullanıcı ID: {$entry->user_id} için çıkış yapıldı.");
            }
        }

        $this->info('Kapanış saatine göre otomatik çıkış işlemi tamamlandı.');
    }
}