<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MenuCategory>
 */
class MenuCategoryFactory extends Factory
{
    protected $model = MenuCategory::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word, // Rastgele bir kategori adı
            'business_id' => Business::inRandomOrder()->first()->id, // Rastgele bir iş id'si al
        ];
    }
}
