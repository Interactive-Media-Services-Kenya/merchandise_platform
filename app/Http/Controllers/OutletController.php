<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index(){
        $outlets  = Outlet::all();

        return view('outlets.index',compact('outlets'));
    }
}
