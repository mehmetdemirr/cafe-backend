<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        // Her işletme için birkaç menü öğesi oluştur
        foreach (Business::all() as $business) {
            // İşletmeye ait kategorileri al
            $categories = MenuCategory::where('business_id', $business->id)->get();

            // Kategoriler mevcutsa menü öğeleri oluştur
            if ($categories->isNotEmpty()) {
                foreach ($categories as $category) {
                    MenuItem::factory()
                        ->count(5) // Her kategori için 5 menü öğesi
                        ->create([
                            'business_id' => $business->id,
                            'menu_category_id' => $category->id,
                        ]);
                }
            }
        }
    }
}
