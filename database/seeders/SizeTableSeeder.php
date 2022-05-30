<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = [
            [
                'id' => 1,
                'name'=> 'S',
            ],
            [
                'id' => 2,
                'name'=> 'M'
            ],
            [
                'id' => 3,
                'name'=> 'XL'
            ],
            [
                'id' => 4,
                'name'=> 'XXL'
            ],
        ];

        Size::insert($sizes);
    }
}
