<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $outlets = [];
        $count = 0;
        for ($i=0; $i < 10; $i++) {
            $count ++;
            $data =[
                'id'=> $count,
                'name' => 'Outlet '.$count,
                'code' => 'OUTLET_CODE'.$count,
                'county_id' => mt_rand(1,47),
            ];
            array_push($outlets,$data);
        }

        Outlet::insert($outlets);

    }
}
