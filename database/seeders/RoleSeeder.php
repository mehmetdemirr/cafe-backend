<?php

namespace Database\Seeders;

use App\enum\UserRoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roller oluştur
        $superAdminRole = Role::create(['name' => UserRoleEnum::SUPERADMIN]);
        $adminRole = Role::create(['name' => UserRoleEnum::ADMIN]);
        $companyRole = Role::create(['name' => UserRoleEnum::BUSINESS]);
        $userRole = Role::create(['name' => UserRoleEnum::USER]);
    }
}
