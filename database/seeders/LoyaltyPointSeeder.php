<?php

namespace Database\Seeders;

use App\Models\LoyaltyPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoyaltyPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoyaltyPoint::factory()->count(10)->create();
    }
}
