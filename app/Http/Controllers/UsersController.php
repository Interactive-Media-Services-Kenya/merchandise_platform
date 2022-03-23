<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\County;
use App\Models\Productbas;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['roles', 'county'])->get();
        // dd($users);

        return view('users.index', compact('users'));
    }

    //get Team leaders all regions

    public function teamleaders()
    {
        $teamleaders = User::with(['roles', 'county'])->where('role_id', 3)->get();


        return view('teamleaders.index', compact('teamleaders'));
    }

    //Get Brand Ambassadors for each team leader

    public function brandambassadors()
    {
        $county_id = Auth::user()->county_id;
        $brandambassadors = User::with(['roles', 'county'])->where('role_id', 4)->where('county_id', $county_id)->get();


        return view('brandambassadors.index', compact('brandambassadors'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('title', 'id');
        $counties = County::pluck('name', 'id');

        return view('users.create', compact('roles', 'counties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'numeric', 'digits:12'],
            'county_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
            'password' => ['required', Password::min(8)->mixedCase()->symbols()->uncompromised(), 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'county_id' => $request->county_id,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
        ]);
        Activity::create([
            'title' => 'User Added',
            'description' => Auth::user()->name . ' Added User: ' . $user->email,
            'user_id' => Auth::id(),
        ]);
        if ($user) {
            Alert::success('Success', 'User Successfully Added');
            return back();
        } else {
            Alert::error('Failed', 'Registration failed');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('title', 'id');
        $counties = County::pluck('name', 'id');

        return view('users.edit', compact('roles', 'user', 'counties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'numeric', 'digits:12'],
            'county_id' => ['required', 'integer'],
            'role_id' => ['required', 'integer'],
        ]);
        $user = User::findOrFail($id);

        if ($user->update($request->all())) {
            Activity::create([
                'title' => 'User Updated',
                'description' => Auth::user()->name . ' Updated User: ' . $user->email,
                'user_id' => Auth::id(),
            ]);
            Alert::success('Success', 'User Details Successfully Edited');
            return back();
        } else {
            Alert::error('Failed', 'Details Not Edited');
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            Activity::create([
                'title' => 'User Deleted',
                'description' => Auth::user()->name . ' Deleted User: ' . $user->email,
                'user_id' => Auth::id(),
            ]);
            Alert::success('Success', 'User Removed Successfully');
            return back();
        } else {
            Alert::error('Failed', 'User Not Deleted');
            return back();
        }
    }
    public function showBa($id)
    {
        $ba = User::findOrFail($id);
        //Get all products assigned to this one ba
        $products = Productbas::where('assigned_to', $id)->get();

        $batches = Productbas::select('batch_id')->where('assigned_to', $id)->groupBy('batch_id')->take(5)->get();

        return view('brandambassadors.show', compact('ba', 'products', 'batches'));
    }
}
