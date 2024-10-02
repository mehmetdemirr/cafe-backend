<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Sanat',
                'image_url' => 'favorite/sanat.png',
                'color' => '#FEFCE8',
            ],
            [
                'name' => 'Robotik',
                'image_url' => 'favorite/robotik.png',
                'color' => '#F0FDFA',
            ],
            [
                'name' => 'Psikoloji',
                'image_url' => 'favorite/psikoloji.png',
                'color' => '#FEF2F2',
            ],
            [
                'name' => 'Pazarlama',
                'image_url' => 'favorite/pazarlama.png',
                'color' => '#F5F3FF',
            ],
            [
                'name' => 'Müzik',
                'image_url' => 'favorite/music.png',
                'color' => '#FFF7ED',
            ],
            [
                'name' => 'Tarih',
                'image_url' => 'favorite/tarih.png',
                'color' => '#F5F3FF',
            ],
            [
                'name' => 'Uzay',
                'image_url' => 'favorite/uzay.png',
                'color' => '#FEF2F2',
            ],
            [
                'name' => 'Felsefe',
                'image_url' => 'favorite/felsefe.png',
                'color' => '#F5F3FF',
            ],
            [
                'name' => 'Günlük',
                'image_url' => 'favorite/gunluk.png',
                'color' => '#FFF7ED',
            ],
            [
                'name' => 'Kültür',
                'image_url' => 'favorite/kultur.png',
                'color' => '#F5F3FF',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
