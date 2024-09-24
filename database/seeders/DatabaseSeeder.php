<?php

namespace Database\Seeders;

use App\enum\UserRoleEnum;
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

        $company = User::factory()->create([
            'name' => 'Company Kullanıcısı',
            'email' => 'company@gmail.com',
        ]);
        //TODO burda işletme ise orda da kayıt edilecek
        // $business = Business::create([
        //     "user_id" => $company->id,
        // ]);
        $company->assignRole(UserRoleEnum::COMPANY);

        $company = User::factory()->create([
            'name' => 'Admin Kullanıcısı',
            'email' => 'admin@gmail.com',
        ]);
        $company->assignRole(UserRoleEnum::ADMIN);

        $this->call([
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
