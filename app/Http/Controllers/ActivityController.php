<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // $activity = Activity::with('user')->get();
        // //dd($activity);
        // return DataTables::eloquent($activity)
        //     ->addColumn('users', function (User $user) {
        //         return $user->user->email;
        //     })
        //     ->toJson();
        if ($request->ajax()) {

            $model = Activity::orderBy('id','DESC')->with('user');

            return DataTables::eloquent($model)

                ->addColumn('users', function (Activity $activity) {

                    return $activity->user->email;
                })

                ->toJson();
        }

        return view('activities.index');
    }
}
