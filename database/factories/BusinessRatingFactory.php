<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\BusinessRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessRating>
 */
class BusinessRatingFactory extends Factory
{
    protected $model = BusinessRating::class;

    public function definition()
    {
        // Kullanıcı ve işletme seçimi
        $user = User::inRandomOrder()->first(); // Rastgele bir kullanıcı al
        $business = Business::inRandomOrder()->first(); // Rastgele bir işletme al

        return [
            'rating' => $this->faker->numberBetween(1, 5), // 1 ile 5 arasında bir puan
            'comment' => $this->faker->sentence(10), // 10 kelimelik bir yorum
            'user_id' => $user->id, // Seçilen kullanıcının kimliği
            'business_id' => $business->id, // Seçilen işletmenin kimliği
        ];
    }
}
