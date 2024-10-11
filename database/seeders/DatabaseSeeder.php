<?php

namespace Database\Seeders;

use App\enum\UserRoleEnum;
use App\Models\Business;
use App\Models\ProfileDetail;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        $user = User::factory()->create([
            'name' => 'User Kullanıcısı',
            'email' => 'user@gmail.com',
        ]);
        ProfileDetail::factory()->create([
            'user_id' => $user->id,
            // Diğer profil detaylarını buraya ekleyebilirsiniz.
            // 'phone_number' => fake()->phoneNumber(),
            // 'biography' => fake()->paragraph(),
            // 'country' => fake()->country(),
            // 'city' => fake()->city(),
            // 'district' => fake()->word(), // veya başka bir uygun değer
            // 'is_premium' => fake()->boolean(), // premium durumu
        ]);
        $user->assignRole(UserRoleEnum::USER);

        $business_user = User::factory()->create([
            'name' => 'Business Kullanıcısı',
            'email' => 'business@gmail.com',
        ]);

        //burda işletme ise orda da kayıt edilecek
        Business::create([
            "user_id" => $business_user->id,
            "slug" => "olga-cafe"
        ]);
        $business_user->assignRole(UserRoleEnum::BUSINESS);

        //admin
        $admin_user = User::factory()->create([
            'name' => 'Admin Kullanıcısı',
            'email' => 'admin@gmail.com',
        ]);
        $admin_user->assignRole(UserRoleEnum::ADMIN);

        $this->call(class: [
            UserSeeder::class, //user ekle
            // BusinessSeeder::class , //business ekle //TODO arada user rolünü business yapıyor gerek yok zaten
            CategorySeeder::class, //genel kategori ekle
            CampaignSeeder::class , // kampanya ekle
            LoyaltyPointSeeder::class ,//sadakat puanı ekle
            BusinessRatingSeeder::class ,//işletme rating ekle
            MenuCategorySeeder::class,//işletme menü category ekle
            MenuItemSeeder::class,//işletme menü item ekle
        ]); 
    }
}
