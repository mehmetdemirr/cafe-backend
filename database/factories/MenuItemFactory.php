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
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 1, 100), // 1 ile 100 arasında rastgele fiyat
            'description' => $this->faker->sentence,
            'menu_category_id' => null, // Burasını seeder'da belirleyeceğiz
            'business_id' => null, // Burasını seeder'da belirleyeceğiz
            'views' => $this->faker->numberBetween(0, 1000),
            'is_available' => $this->faker->boolean,
            'additional_info' => $this->faker->sentence,
            'calories' => $this->faker->numberBetween(50, 500),
        ];
    }
}
