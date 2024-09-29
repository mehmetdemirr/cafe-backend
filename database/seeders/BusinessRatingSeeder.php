<?php

namespace Database\Seeders;

use App\Models\BusinessRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessRatingSeeder extends Seeder
{
    public function run()
    {
        $users = \App\Models\User::all(); // Tüm kullanıcıları al
        $businesses = \App\Models\Business::all(); // Tüm işletmeleri al

        foreach ($businesses as $business) {
            foreach ($users as $user) {
                // Her kullanıcı için işletmeye yalnızca bir değerlendirme ekle
                BusinessRating::firstOrCreate([
                    'user_id' => $user->id,
                    'business_id' => $business->id,
                ], [
                    'rating' => rand(1, 5), // Rastgele bir puan
                    'comment' => 'Bu bir örnek yorumdur.', // Varsayılan yorum
                ]);
            }
        }
    }
}
