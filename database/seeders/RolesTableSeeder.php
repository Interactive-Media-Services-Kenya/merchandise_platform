<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'title'=> 'Super Admin'
            ],
            [
                'id' => 2,
                'title'=> 'True Blaq'
            ],
            [
                'id' => 3,
                'title'=> 'Team Leader'
            ],
            [
                'id' => 4,
                'title'=> 'Brand Ambassador'
            ],
            [
                'id' => 5,
                'title'=> 'Other'
            ],
        ];

        Role::insert($roles);
    }
}
