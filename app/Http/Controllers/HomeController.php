<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('home', compact('products','batches','clients','bas'));
    }
}
