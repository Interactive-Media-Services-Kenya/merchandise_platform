<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'id'=>1,
                'name'=> 'Create User',
            ],
            [
                'id'=>2,
                'name'=> 'Edit User',
            ],
            [
                'id'=>3,
                'name'=> 'Delete User',
            ],
            [
                'id'=>4,
                'name'=> 'Create Campaign',
            ],
            [
                'id'=>5,
                'name'=> 'Edit Campaign',
            ],
            [
                'id'=>6,
                'name'=> 'Delete Campaign',
            ],
            [
                'id'=>7,
                'name'=> 'Assign Campaign',
            ],
            [
                'id'=>8,
                'name'=> 'Generate MerchandiseCodes',
            ],
            [
                'id'=>9,
                'name'=> 'Upload Merchandise',
            ],
            [
                'id'=>10,
                'name'=> 'Issue Merchandise',
            ],
        ];
        foreach ($permissions as $permission){
            $perm = Permission::create($permission);
            $admins = User::where('role_id',1)->get();
            foreach ($admins as $key=>$admin){
                $admin->permission_users()->attach($perm->id);
            }
        }
    }
}
