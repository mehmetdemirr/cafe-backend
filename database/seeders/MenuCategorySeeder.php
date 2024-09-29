<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Örnek olarak her bir iş için 3 kategori oluştur
         \App\Models\Business::all()->each(function ($business) {
            MenuCategory::factory()->count(3)->create(['business_id' => $business->id]);
        });
    }
}
