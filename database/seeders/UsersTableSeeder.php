<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'=> 'Super Admin',
                'phone' => '245713218312',
                'email'=> 'stephen@ims.co.ke',
                'role_id'=>1,
                'county_id' => mt_rand(1,47),
                'password'=>  bcrypt('Owagostv@123'),
            ],
            [
                'name'=> 'True Blaq',
                'phone' => mt_rand(254700000000, 254799999999),
                'email'=> 'trueblaq@ims.co.ke',
                'role_id'=>2,
                'county_id' => mt_rand(1,47),
                'password'=>  bcrypt('password'),
            ],
            [
                'name'=> 'Brand Ambassador',
                'phone' =>mt_rand(254700000000, 254799999999),
                'email'=> 'ba@ims.co.ke',
                'role_id'=>4,
                'county_id' => mt_rand(1,47),
                'password'=>  bcrypt('password'),
            ],
            [
                'name'=> 'Brand Ambassador 1',
                'phone' => mt_rand(254700000000, 254799999999),
                'email'=> 'ba1@ims.co.ke',
                'role_id'=>4,
                'county_id' => mt_rand(1,47),
                'password'=>  bcrypt('password'),
            ],
            [
                'name'=> 'Team Leader 1',
                'phone' =>  mt_rand(254700000000, 254799999999),
                'email'=> 'teamleader1@ims.co.ke',
                'role_id'=>3,
                'county_id' => mt_rand(1,47),
                'password'=>  bcrypt('password'),
            ],
            [
                'name'=> 'Team Leader 2',
                'phone' =>  mt_rand(254700000000, 254799999999),
                'email'=> 'teamleader2@ims.co.ke',
                'role_id'=>3,
                'county_id' => mt_rand(1,47),
                'password'=>  bcrypt('password'),
            ],
        ];

        User::insert($users);
        DB::table('users')->insert(
            [
                'name'=> 'Client One',
                'phone' =>  mt_rand(254700000000, 254799999999),
                'email'=> 'clientone@ims.co.ke',
                'role_id'=>5,
                'county_id' => mt_rand(1,47),
                'password'=>  bcrypt('password'),
                'client_id'=>1,
            ],
        );
    }
}
