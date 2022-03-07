<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
                'password'=>  bcrypt('Owagostv@123'),
            ],
        ];

        User::insert($users);
    }
}
