<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;



class UsersImport implements ToModel, WithHeadingRow
{
    // protected $sendSMSService;

    // public function __construct(SendSMSService $sendSMSService)
    // {
    //     $this->sendSMSService = $sendSMSService;
    // }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function model(array $row)
    {
        $password = rand(1000,9999);

        return new User([
            "name" => $row['name'] ,
            "email" => $row['email']??$row['name'].'@merchandise.com',
            "phone" => $row['phone'],
            "county_id" => $row['county_id'],
            "role_id" => 2, // User Type User
            "password" => bcrypt($password)
        ]);
    }
}
