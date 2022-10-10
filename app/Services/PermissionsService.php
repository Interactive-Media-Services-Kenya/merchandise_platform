<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\Gate;

/**
 * Class PermissionsService.
 */
class PermissionsService
{
     public function getPermissions($permissionName){
         $permission = Permission::wherename($permissionName)->first();
         if (!$permission){
             return false;
         }
         $userPermissions = \DB::table('permission_user')->whereuser_id(\Auth::id())->wherepermission_id($permission->id)->first();
         if (!$userPermissions){
             return false;
         }
         return true;
     }
}
