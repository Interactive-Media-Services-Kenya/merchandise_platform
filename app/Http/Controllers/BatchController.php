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
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\AssignMerchandise;
use App\Models\Activity;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

class BatchController extends Controller
{
    public function index()
    {
        $batchesTls = Product::select('*')->where('assigned_to',Auth::id())->groupBy('batch_id')->get();

        $batchesbas = Productbas::select('*')->where('assigned_to', Auth::id())->groupBy('batch_id')->get();

        return view('batches.index', compact('batchesbas','batchesTls'));
    }


    public function show($id)
    {
        $batch = Batch::findOrFail($id);
        //Products Brand Ambassodors
        $productaccepted = Product::select('id')->where('batch_id', $id)->where('accept_status', 0)->get();
        $productRejects = Reject::select('product_id')->where('user_id', Auth::id())->whereIn('product_id', $productaccepted)->get();
        $products = Productbas::select('*')->whereIn('batch_id', $batch)->whereIn('product_id', $productaccepted)->whereNotIn('product_id', $productRejects)->where('assigned_to', Auth::id())->get();

        //Products Team Leaders
        $productsTl = Product::where('batch_id', $id)
            ->join('batches', 'batches.id', 'products.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->get();
        // dd($products);
        //Rejecting Reasons
        $reasons = Reason::all();
        return view('batches.show', compact('batch', 'products', 'reasons', 'productsTl'));
    }
    public function confirmBatch($id)
    {

        $productsTl = Product::where('batch_id', $id)
            ->join('batches', 'batches.id', 'products.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->where('batches.accept_status', 0)->get();
        if (count($productsTl) > 0) {
            $batch = Batch::findOrFail($id);

            $batch->update([
                'accept_status' => 1,
            ]);
            Activity::create([
                'title'=> 'Confirm Batch',
                'user_id' => Auth::id(),
                'description' => Auth::user()->name.' have confirmed Batch: '.$batch->batch_code ,
            ]);
            Alert::success('Success','Operation Successfull');
            return back();
        } else {
            Alert::error('Failed', 'Batch is Already Confirmed');
            return back();
        }
    }

    //Team Leader rejects Merrchandise in batch
    public function rejectBatch(Request $request, $id)
    {

        $productsTl = Product::where('batch_id', $id)
                            ->join('batches', 'batches.id', 'products.batch_id')
                            ->where('batches.tl_id_accept', Auth::id())->where('batches.accept_status', 0)->get();
        if (count($productsTl) > 0) {
            foreach ($productsTl as $product) {
                // $product = Product::findOrFail($product->id);
                // $product->update([
                //     'accept_status' => 0,
                // ]);
                $batch = Batch::findOrFail($id);

                $batch->update([
                    'accept_status' => 0,
                ]);

                $reason = Reject::create([
                    'reason_id' => $request->reason_id,
                    'user_id' => Auth::id(),
                    'description' => $request->description,
                    'product_id' => $product->id,
                ]);
            }

            $product = $productsTl->first();
            $productsCount = count($productsTl);
            $merchandise_type = $product->category->title;
            $batchcode = $product->batch->batch_code;
            $sender_email = Auth::user()->email;
            $receiver_email = $product->user->email;
            dd($receiver_email);
            $url_login = URL::to('/login');
            $message = "Hello, Merchandise ($merchandise_type), $productsCount from Batch-Code $batchcode, has been rejected by $sender_email. Kindly Confirm through the portal: $url_login.";
            $details = [
                'title' => 'Mail From ' . $sender_email,
                'body' => $message,
            ];

            Mail::to($receiver_email)->send(new AssignMerchandise($details));
            Alert::success('Success', 'Operation Successfull. An Email has been sent to ' . $receiver_email);
            return back();
        } else {
            Alert::error('Failed!', 'Products in Batch Have Already Been Confirmed!');
            return back();
        }
    }
}
