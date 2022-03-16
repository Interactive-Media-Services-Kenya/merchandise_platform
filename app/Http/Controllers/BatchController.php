<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\IssueProduct;
use App\Models\Product;
use App\Models\Productbas;
use App\Models\Reason;
use App\Models\Reject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    public function index(){

        $batchesbas = Productbas::select('*')->where('assigned_to',Auth::id())->groupBy('batch_id')->get();

        return view('batches.index',compact('batchesbas'));
    }


    public function show($id){
        $batch = Batch::findOrFail($id);
        $productaccepted = Product::select('id')->where('batch_id',$id)->where('accept_status',0)->get();
        $productRejects = Reject::select('product_id')->where('user_id',Auth::id())->whereIn('product_id',$productaccepted)->get();
        $products = Productbas::select('*')->whereIn('batch_id',$batch)->whereIn('product_id', $productaccepted)->whereNotIn('product_id',$productRejects)->where('assigned_to',Auth::id())->get();
        // dd($products);
        //Rejecting Reasons
        $reasons = Reason::all();
        return view('batches.show', compact('batch','products','reasons'));
    }
}
