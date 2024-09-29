<?php

namespace Database\Factories;

use App\Models\LoyaltyPoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoyaltyPoint>
 */
class LoyaltyPointFactory extends Factory
{
    protected $model = LoyaltyPoint::class;

    public function definition()
    {
        return [
            'points' => $this->faker->numberBetween(1, 100), // 1 ile 100 arasında rastgele puan
            'user_id' => User::all()->random()->id,
        ];
    }
}
