<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Productbas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    public function index(){
        #
    }
    public function show($id){
        $batch = Batch::findOrFail($id);
        $products = Productbas::select('*')->whereIn('batch_id',$batch)->where('assigned_to',Auth::id())->get();
        // dd($products);
        return view('batches.show', compact('batch','products'));
    }
}
