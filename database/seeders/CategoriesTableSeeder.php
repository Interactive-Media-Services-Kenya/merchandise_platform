<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'id'=>1,
                'title'=> 'T-Shirts',
            ],
            [
                'id'=>2,
                'title'=> 'Hoodies',
            ],
            [
                'id'=>3,
                'title'=> 'Bags',
            ],
            [
                'id'=>4,
                'title'=> 'Mugs/Cups',
            ],
            [
                'id'=>5,
                'title'=> 'Caps',
            ],
        ];

        Category::insert($categories);
    }
}
