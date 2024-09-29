<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuItem>
 */
class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        // Rastgele bir işletme al
        $business = Business::all()->random();
        
        // İşletmeye ait kategorileri al
        $category = MenuCategory::where('business_id', $business->id)->get()->random();

        return [
            'name' => $this->faker->words(2, true), // Faker ile rastgele bir yemek adı
            'price' => $this->faker->randomFloat(2, 5, 100), // 5 ile 100 arasında rastgele bir fiyat
            'description' => $this->faker->sentence(10), // 10 kelimeden oluşan bir açıklama
            'menu_category_id' => $category->id, // Rastgele bir kategori
            'business_id' => $business->id, // Rastgele işletmenin ID'si
            'views' => $this->faker->numberBetween(0, 1000), // 0 ile 1000 arasında rastgele görüntülenme sayısı
            'is_available' => $this->faker->boolean(80), // %80 ihtimalle mevcut
            'additional_info' => $this->faker->sentence(5), // 5 kelimeden oluşan ek bilgi
            'calories' => $this->faker->numberBetween(100, 1000), // 100 ile 1000 arasında rastgele kalori
        ];
    }
}
