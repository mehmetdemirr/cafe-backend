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
        $business = Business::create([
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
            // BusinessSeeder::class, //işletme ekle
            // CategorySeeder::class, //genel kategori ekle
            // BrandSeeder::class, //genele marka ekle
            // UserSeeder::class, //kullanıcı ekle
            // ProductSeeder::class, //ürün ekle
            // // ProductImageSeeder::class, //ürünlere fotoğraf ekle
            // // CartSeeder::class, //kullanıcıya sepet ekle
            // // CartItemSeeder::class, //sepete item ekle
            // // OrderSeeder::class, //sipariş oluştur
            // // ReviewSeeder::class, //ürünlere yorum ekle
        ]); 
    }
}
