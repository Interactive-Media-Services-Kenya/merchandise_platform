<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Productbas;
use App\Models\User;
use App\Models\Reject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use RealRashid\SweetAlert\Facades\Alert;

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
        if (auth()->user()->password_changed_at == null) {
            return view('auth.first_login.reset');
        } else {

            //Admin Data
            if (Gate::allows('admin_access')) {

                $productsAdmin = Product::with(['category', 'assign', 'batch', 'client'])->select('products.*')->count();
                $batches = Batch::join('storages', 'storages.id', 'batches.storage_id')->where('storages.client_id', null)->cursor();
                $activityAdmin = Activity::select('*')->latest()->take(9)->cursor();
                $bas = User::where('role_id', 4)->count();
                $clients = Client::all();
               // $tls = User::where('role_id', 3)->where('client_id', null)->cursor();
                if ($request->ajax()) {

                    $model = User::orderBy('id', 'DESC')->with('roles');

                    return DataTables::eloquent($model)

                        ->addColumn('role', function (User $user) {

                            return $user->roles->title;
                        })
                        ->toJson();
                }



                return view('home', compact(
                    'productsAdmin',
                    'batches',
                    //'tls',
                    'clients',
                    'batches',
                    'activityAdmin',
                    'bas',
                ));
            }

            //Agency Data
            if (Gate::allows('tb_access')) {
                $rejects = Reject::select('product_id')->cursor();
                $products = Product::where('owner_id', Auth::id())->cursor();
                $batches = Batch::join('storages', 'storages.id', 'batches.storage_id')->where('storages.client_id', null)->cursor();
                $tls = User::where('role_id', 3)->where('client_id', null)->cursor();
                $clients = Client::all();
                $batchesConfirmed = DB::table('batch_teamleaders')->whereaccept_status(1)->whereteam_leader_id(Auth::id())->take(5)->cursor();
                $activities = Activity::orderBy('created_at', 'DESC')->where('user_id', Auth::id())->take(5)->cursor();

                if ($request->ajax()) {

                    $model = User::orderBy('id', 'DESC')->with('roles');

                    return DataTables::eloquent($model)

                        ->addColumn('role', function (User $user) {

                            return $user->roles->title;
                        })
                        ->toJson();
                }



                return view('home', compact(
                    'products',
                    'rejects',
                    'batches',
                    'tls',
                    'clients',
                    'batchesConfirmed',
                    'activities',
                ));
            }
            //TeamLeader Data
            if (Gate::allows('team_leader_access')) {
                $rejects = Reject::select('product_id')->cursor();
                $productsTls = Product::where('assigned_to', Auth::id())->whereNotIn('product_id', $rejects)->cursor();
                $batchesTl = Product::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->cursor();
                $productsIssuedOut = Productbas::all();
                $productsIssuedOutTL = Productbas::join('batches', 'batches.id', 'productbas.batch_id')
                    ->where('batches.tl_id_accept', Auth::id())->cursor();
                $user_id = Auth::id();
                $brandAmbassadors =  User::where('role_id', 4)->where('teamleader_id', $user_id)->cursor();
                $clientsWithMerchandiseTL = Product::select('client_id')->where('assigned_to', Auth::id())->groupBy('client_id')->cursor();
                $activities = Activity::orderBy('created_at', 'DESC')->where('user_id', Auth::id())->take(5)->cursor();
                return view('home', compact(
                    'productsTls',
                    'batchesTl',
                    'productsIssuedOut',
                    'productsIssuedOutTL',
                    'brandAmbassadors',
                    'clientsWithMerchandiseTL',
                    'activities',
                ));
            }

            // BrandAmbassador Data
            if (Gate::allows('brand_ambassador_access')) {
                $rejects = Reject::select('product_id')->cursor();
                $productsTls = Product::where('assigned_to', Auth::id())->whereNotIn('product_id', $rejects)->cursor();
                $batchesTl = Product::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->cursor();
                $productsIssuedOut = Productbas::all();
                $productsIssuedOutTL = Productbas::join('batches', 'batches.id', 'productbas.batch_id')
                    ->where('batches.tl_id_accept', Auth::id())->cursor();
                $activities = Activity::orderBy('created_at', 'DESC')->where('user_id', Auth::id())->take(5)->cursor();
                $productsbas = Product::where('ba_id', Auth::id())->cursor();
                $batchesbas = DB::table('batch_brandambassadors')->wherebrand_ambassador_id(Auth::id())->cursor();
                $categories = Category::where('client_id', null)->get();
                return view('home', compact(
                    'productsbas',
                    'batchesbas',
                    'productsIssuedOut',
                    'productsIssuedOutTL',
                    'categories',
                    'activities',
                ));
            }

            //Client Data
            if (Gate::allows('client_access')) {
                $batchesClient = Batch::join('storages', 'storages.id', 'batches.storage_id')->where('storages.client_id', Auth::user()->client_id)->cursor();
                $clients = Client::all();
                $categoriesClient = Category::where('client_id', Auth::user()->client_id)->cursor();
            }

            // //Batches for a client

            // $bas = User::where('role_id', 4)->get();
            // $categories = Category::where('client_id', null)->get();
            // $tls = User::where('role_id', 3)->where('client_id', null)->get();
            // $salesreps = User::where('role_id', 3)->where('client_id', Auth::user()->client_id)->get();
            // // ? Products issued Out
            // $productsIssuedOut = Productbas::all();
            // $productsIssuedOutTL = Productbas::join('batches', 'batches.id', 'productbas.batch_id')
            //     ->where('batches.tl_id_accept', Auth::id())->get();
            // //Get Bas under a team leader
            // $user_id = Auth::id();
            // $brandAmbassadors =  User::where('role_id', 4)->where('teamleader_id', $user_id)->get();


            // // ? Brand Ambassador Data
            // // ? Products for a Ba
            // $productsbas = Productbas::where('assigned_to', Auth::id())->whereNotIn('product_id', $rejects)->get();
            // // Batches ba

            // $batchesbas = Productbas::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->get();
            // $activityAdmin = Activity::select('*')->latest()->take(5)->get();
            // //dd($activityAdmin);



        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ],
        ]);

        $user = User::findOrFail(Auth::id());

        $password = Hash::make($request->password);
        $user->update([
            'password' => $password,
            'password_changed_at' => \Carbon\Carbon::now()
        ]);

        if ($user) {
            Alert::success('Success', 'Operation Successful');
            return redirect()->route('home');
        } else {

            Alert::error('Failed', 'Password Not Changed');
            return redirect()->route('home');
        }
    }
}
