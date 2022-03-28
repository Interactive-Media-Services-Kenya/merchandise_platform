<?php

namespace Database\Seeders;

use App\Models\Storage;
use Illuminate\Database\Seeder;

class StoragesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storages = [
            [
                'id' => 1,
                'title'=> 'Storage 1'
            ],
            [
                'id' => 2,
                'title'=> 'Storage 2'
            ],
            [
                'id' => 3,
                'title'=> 'Storage 3'
            ],
        ];

        Storage::insert($storages);
    }
}
