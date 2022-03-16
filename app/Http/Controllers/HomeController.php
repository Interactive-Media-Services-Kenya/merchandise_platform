<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Productbas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        //Admin data

        //All products
        $products = Product::all();
        $batches = Batch::all();
        $clients = Client::all();
        $bas = User::where('role_id', 4)->get();
        $categories = Category::all();

        //Products Ba
        $productsbas = Productbas::where('assigned_to',Auth::id())->get();
        // Batches ba

        $batchesbas = Productbas::select('*')->where('assigned_to',Auth::id())->groupBy('batch_id')->get();

        return view('home', compact('products','batches','clients','bas','productsbas','batchesbas','categories'));
    }
}
