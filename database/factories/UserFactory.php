<?php

namespace Database\Factories;

use App\enum\UserRoleEnum;
use App\Models\ProfileDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Create a user with the 'user' role.
     */
    public function withUserRole(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(UserRoleEnum::USER);

            // ProfileDetail oluştur
            ProfileDetail::factory()->create([
                'user_id' => $user->id,
                // // Diğer profil detaylarını buraya ekleyebilirsiniz.
                // 'phone_number' => fake()->phoneNumber(),
                // 'biography' => fake()->paragraph(),
                // 'country' => fake()->country(),
                // 'city' => fake()->city(),
                // 'district' => fake()->word(), // veya başka bir uygun değer
                // 'is_premium' => fake()->boolean(), // premium durumu
            ]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
