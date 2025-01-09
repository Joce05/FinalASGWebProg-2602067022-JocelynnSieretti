<?php

namespace Database\Seeders;

use App\Models\Avatar;
use Illuminate\Database\Seeder;

class AvatarSeeder extends Seeder
{
    public function run(): void
    {

        // Avatar::truncate();


        $avatars = [
            [
                'image_path' => 'assets/avatar1.png',
                'price' => 50
            ],
            [
                'image_path' => 'assets/avatar2.png',
                'price' => 500
            ],
            [
                'image_path' => 'assets/avatar3.png',
                'price' => 10000
            ],
            [
                'image_path' => 'assets/avatar4.png',
                'price' => 100000
            ]
        ];

        foreach ($avatars as $avatar) {
            Avatar::create($avatar);
        }
    }
}
