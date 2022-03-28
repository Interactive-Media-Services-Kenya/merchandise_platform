<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();
        $this->call([
            CountiesTableSeeder::class,
            RolesTableSeeder::class,
            CategoriesTableSeeder::class,
            StoragesTableSeeder::class,
            UsersTableSeeder::class,
            ClientsTableSeeder::class,
            ReasonsTableSeeder::class,
        ]);
    }
}
