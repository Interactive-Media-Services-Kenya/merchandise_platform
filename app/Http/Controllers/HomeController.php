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
            //Admin data & TB Data
            $rejects = Reject::select('product_id')->get();
            $productsAdmin = count(Product::with(['category', 'assign', 'batch', 'client'])->select('products.*')->cursor());
            //All products
            $products = Product::where('owner_id', Auth::id())->get();
            $batches = Batch::join('storages', 'storages.id', 'batches.storage_id')->where('storages.client_id', null)->get();
            //Batches for a client
            $batchesClient = Batch::join('storages', 'storages.id', 'batches.storage_id')->where('storages.client_id', Auth::user()->client_id)->get();
            $clients = Client::all();
            $categoriesClient = Category::where('client_id', Auth::user()->client_id)->get();

            $bas = User::where('role_id', 4)->get();
            $categories = Category::where('client_id', null)->get();
            $tls = User::where('role_id', 3)->where('client_id', null)->get();
            $salesreps = User::where('role_id', 3)->where('client_id', Auth::user()->client_id)->get();
            // ? Products issued Out
            $productsIssuedOut = Productbas::all();
            $productsIssuedOutTL = Productbas::join('batches', 'batches.id', 'productbas.batch_id')
                ->where('batches.tl_id_accept', Auth::id())->get();
            //? Clients with Merchandise Team Leaders
            $clientsWithMerchandiseTL = Product::select('client_id')->where('assigned_to', Auth::id())->groupBy('client_id')->get();

            //True Blaq
            $batchesConfirmed = Batch::orderBy('updated_at', 'DESC')->where('accept_status', 1)->take(5)->get();
            //dd($batchesConfirmed);
            // ? Team Leader Data
            // ? Product for a team leader
            $productsTls = Product::where('assigned_to', Auth::id())->whereNotIn('product_id', $rejects)->get();
            $batchesTl = Product::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->get();
            // dd($batchesTl);
            //Get Bas under a team leader
            $user_id = Auth::id();
            $brandAmbassadors =  User::where('role_id', 4)->where('teamleader_id', $user_id)->get();


            // ? Brand Ambassador Data
            // ? Products for a Ba
            $productsbas = Productbas::where('assigned_to', Auth::id())->whereNotIn('product_id', $rejects)->get();
            // Batches ba

            $batchesbas = Productbas::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->get();
            $activityAdmin = Activity::select('*')->latest()->take(5)->get();
            //dd($activityAdmin);

            //user activity data
            $activities = Activity::orderBy('created_at', 'DESC')->where('user_id', Auth::id())->take(5)->get();
            // dd($activities);
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
                'batches',
                'batchesClient',
                'clients',
                'categoriesClient',
                'bas',
                'tls',
                'salesreps',
                'productsbas',
                'batchesbas',
                'categories',
                'batchesConfirmed',
                'productsTls',
                'brandAmbassadors',
                'batchesTl',
                'activities',
                'activityAdmin',
                'productsIssuedOut',
                'productsIssuedOutTL',
                'clientsWithMerchandiseTL',
                'productsAdmin'
            ));
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
