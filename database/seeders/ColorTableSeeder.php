<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            [
                'id' => 1,
                'name'=> 'RED',
            ],
            [
                'id' => 2,
                'name'=> 'YELLOW'
            ],
            [
                'id' => 3,
                'name'=> 'GREEN'
            ],
            [
                'id' => 4,
                'name'=> 'WHITE'
            ],
            [
                'id' => 5,
                'name'=> 'ORANGE'
            ],
            [
                'id' => 6,
                'name'=> 'BLACK'
            ],
            [
                'id' => 7,
                'name'=> 'BLUE'
            ],
            [
                'id' => 8,
                'name'=> 'GRAY'
            ],
        ];

        Color::insert($colors);
    }
}
