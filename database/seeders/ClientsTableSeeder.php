<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'id' => 1,
                'name' => 'East African Breweries Limited',
                'email' => 'info@eabl.com',
                'phone' => mt_rand(254700000000, 254799999999),
                'created_by' => 1,
                'address' => 'P.O Box 1234 Nairobi',
            ],
            [
                'id' => 2,
                'name' => 'Coca Cola Kenya',
                'email' => 'info@cokekenya.com',
                'phone' => mt_rand(254700000000, 254799999999),
                'created_by' => 1,
                'address' => 'P.O Box 1234 Nairobi',
            ],
            [
                'id' => 3,
                'name' => 'Brookside Diaries',
                'email' => 'info@brookside.com',
                'phone' => mt_rand(254700000000, 254799999999),
                'created_by' => 1,
                'address' => 'P.O Box 1234 Nairobi',
            ],
            [
                'id' => 4,
                'name' => 'Telcom Kenya',
                'email' => 'info@telcom.com',
                'phone' => mt_rand(254700000000, 254799999999),
                'created_by' => 1,
                'address' => 'P.O Box 1234 Nairobi',
            ],
        ];

        Client::insert($clients);
    }
}
