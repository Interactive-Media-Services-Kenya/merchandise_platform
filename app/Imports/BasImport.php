<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class BasImport implements ToModel
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
            "role_id" => 4,  // User Type User : BrandAmbassador
            "teamleader_id"=>$row['teamleader_id'],
            "password" => bcrypt($password)
        ]);
    }

}
