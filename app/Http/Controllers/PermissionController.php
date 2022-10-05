<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use DB;

class PermissionController extends Controller
{
    public function index(){
        $permissions = Permission::all();
        return view('permissions.index',compact('permissions'));
    }

    public function create(){
        return view('permissions.create');
    }

    public function store(Request  $request){
        $request->validate([
            'name'=> 'required'
        ]);
        //Get all Admins.
        $admins = User::where('role_id',1)->get();

        //Create Permission and assign to all admins
        $permission = Permission::create($request->all());
        foreach ($admins as $key=>$admin){
            $admin->permission_users()->attach($permission->id);
        }
        if ($permission){
            Alert::success('Success','Permission Created Sucessfully');
            return redirect()->route('permissions.index');
        }else{
            Alert::error('Failed','Permission Not Registered');
            return back();
        }

    }

    public function edit(Permission $permission){

        return view('permissions.edit',compact('permission'));
    }

    public function update(Request $request, Permission $permission){
        $request->validate([
            'name' => 'required|string',
        ]);
        $permission->update($request->all());
        Alert::success('Success','Permission Updated Successfully');
        return redirect()->route('permissions.index');
    }
    public function destroy(Permission $permission){
        //Detach all users associated with the Permission
        $ids = DB::table('permission_user')->select('user_id')->wherepermission_id($permission->id)->get();
            foreach ($ids as $id){
                $permission->users()->detach($id);
            }
        if ($permission->delete()){
            Alert::success('Success', 'Permission Deleted Succesffully');
            return redirect()->route('permissions.index');
        }
        Alert::error('Failed','Permission not removed!');
        return back();

    }
}
