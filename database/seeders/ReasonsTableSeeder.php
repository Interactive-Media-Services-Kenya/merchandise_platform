<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Seeder;

class ReasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            [
                'id' => 1,
                'title'=> 'Merchandise count tally is below the expected amount',
            ],
            [
                'id' => 2,
                'title'=> 'Merchandise is broken'
            ],
            [
                'id' => 3,
                'title'=> 'Wrong Merchandise Type'
            ],
            [
                'id' => 4,
                'title'=> 'Other'
            ],
        ];

        Reason::insert($reasons);
    }
}
