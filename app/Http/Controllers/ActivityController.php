<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
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
