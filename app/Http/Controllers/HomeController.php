<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Productbas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        //Admin data & TB Data

        //All products
        $products = Product::all();
        $batches = Batch::all();
        $clients = Client::all();
        $bas = User::where('role_id', 4)->get();
        $categories = Category::all();
        $tls = User::where('role_id',3)->get();

        //True Blaq
        $batchesConfirmed = Batch::orderBy('updated_at', 'DESC')->where('accept_status',1)->take(5)->get();
        //dd($batchesConfirmed);
        //Team Leader Data
        // Product for a team leader
        $productsTls = Product::where('assigned_to', Auth::id())->get();
        $batchesTl = Product::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->get();
        // dd($batchesTl);
        //Get Bas under a team leader
        $region_id = Auth::user()->county_id;
        $brandAmbassadors =  User::where('role_id', 4)->where('county_id', $region_id)->get();


        //Brand Ambassador Data
        //Products for a Ba
        $productsbas = Productbas::where('assigned_to',Auth::id())->get();
        // Batches ba

        $batchesbas = Productbas::select('*')->where('assigned_to',Auth::id())->groupBy('batch_id')->get();
        $activityAdmin = Activity::select('*')->latest()->take(5)->get();
        //dd($activityAdmin);

        //user activity data
        $activities = Activity::orderBy('created_at', 'DESC')->where('user_id',Auth::id())->take(5)->get();
        // dd($activities);
        if ($request->ajax()) {

            $model = User::orderBy('id','DESC')->with('roles');

            return DataTables::eloquent($model)

                ->addColumn('role', function (User $user) {

                    return $user->roles->title;
                })
                ->toJson();
        }



        return view('home', compact('products','batches','clients','bas','tls',
                                    'productsbas','batchesbas','categories','batchesConfirmed',
                                    'productsTls', 'brandAmbassadors','batchesTl','activities','activityAdmin'));
    }

}
