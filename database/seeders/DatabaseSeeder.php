<?php

namespace Database\Seeders;

use App\enum\UserRoleEnum;
use App\Models\Business;
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
        $user->assignRole(UserRoleEnum::USER);

        $business = User::factory()->create([
            'name' => 'Business Kullanıcısı',
            'email' => 'business@gmail.com',
        ]);

        //burda işletme ise orda da kayıt edilecek
        Business::create([
            "user_id" => $business->id,
        ]);
        $business->assignRole(UserRoleEnum::BUSINESS);

        //admin
        $admin = User::factory()->create([
            'name' => 'Admin Kullanıcısı',
            'email' => 'admin@gmail.com',
        ]);
        $admin->assignRole(UserRoleEnum::ADMIN);

        $this->call(class: [
            UserSeeder::class, //user ekle
            BusinessSeeder::class , //business ekle
            CategorySeeder::class, //genel kategori ekle
            CampaignSeeder::class , // kampanya ekle
            LoyaltyPointSeeder::class ,//sadakat puanı ekle
            BusinessRatingSeeder::class ,//işletme rating ekle
            MenuCategorySeeder::class,//işletme menü category ekle
            MenuItemSeeder::class,//işletme menü item ekle
        ]); 
    }
}
