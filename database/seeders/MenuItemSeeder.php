<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run()
    {
        // Her işletme için birkaç menü öğesi oluştur
        foreach (\App\Models\Business::all() as $business) {
            MenuItem::factory()
                ->count(5) // Her işletme için 5 menü öğesi
                ->create(['business_id' => $business->id]);
        }
    }
}
