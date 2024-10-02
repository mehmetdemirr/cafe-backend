<?php

namespace Database\Factories;

use App\enum\UserRoleEnum;
use App\Interfaces\BusinessRepositoryInterface;
use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition()
    {
        // // Kullanıcıyı mevcut kullanıcılar arasından rastgele seç
        // $user = User::inRandomOrder()->first();
        
        // // Kullanıcıya "business" rolünü atayın
        // $user->syncRoles([UserRoleEnum::BUSINESS]);

        // // Eğer kullanıcıya ait bir business yoksa oluştur
        // if (!Business::where('user_id', $user->id)->exists()) {
        //     return [
        //         'user_id' => $user->id,
        //         'name' => $this->faker->company,
        //         'address' => $this->faker->address,
        //         'qr_code' => $this->faker->uuid,
        //     ];
        // }

        // // Eğer zaten bir business varsa, boş bir array döndür
        // return [];
    }
}
