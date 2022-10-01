<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\IssueProduct;
use App\Models\Product;
use App\Models\Productbas;
use App\Models\Reason;
use App\Models\Reject;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\AssignMerchandise;
use App\Models\Activity;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Services\SendSMSService;
use DB;

class BatchController extends Controller
{
    protected $sendSMSService;

    public function __construct(SendSMSService $sendSMSService)
    {
        $this->sendSMSService = $sendSMSService;
    }

    public function index()
    {
        $batchesTls = DB::table('batch_teamleaders')->whereteam_leader_id(Auth::id())->orderBy('created_at','DESC')->cursor();

        $batchesbas = DB::table('batch_brandambassadors')->wherebrand_ambassador_id(Auth::id())->cursor();


        return view('batches.index', compact('batchesbas', 'batchesTls'));
    }

    public function show($id)
    {
        //Batch for agency
        if (Gate::allows('tb_access')) {
            $batch = DB::table('batches')->whereid($id)->first();
            if($batch == null){
                Alert::error('Failed','No Batch Found!');
                return back();
            }
            $products = Product::whereowner_id(Auth::id())->wherebatch_id($id)->cursor();

            $reasons = Reason::all();
            return view('batches.show', compact('batch', 'products', 'reasons'));
        }
        if (Gate::allows('team_leader_access')) {
            $batch = DB::table('batch_teamleaders')->whereid($id)->first();
            if($batch == null){
                Alert::error('Failed','No Batch Found!');
                return back();
            }
            $products = Product::whereassigned_to(Auth::id())->wherebatch_tl_id($id)->cursor();

            $reasons = Reason::all();
            return view('batches.show', compact('batch', 'products', 'reasons'));
        }

        if (Gate::allows('brand_ambassador_access')) {
            $batch = DB::table('batch_brandambassadors')->whereid($id)->first();
            if($batch == null){
                Alert::error('Failed','No Batch Found!');
                return back();
            }
            $products = Product::whereba_id(Auth::id())->wherebatch_ba_id($id)->cursor();

            $reasons = Reason::all();
            return view('batches.show', compact('batch', 'products', 'reasons'));
        }
    }

    public function confirmBatch($id)
    {
        if (Gate::allows('team_leader_access')) {
            $productsTl = Product::where('batch_id', $id)
                ->join('batches', 'batches.id', 'products.batch_id')
                ->where('batches.tl_id_accept', Auth::id())->where('batches.accept_status', 0)->get();
            if (count($productsTl) > 0) {
                $batch = Batch::findOrFail($id);

                $batch->update([
                    'accept_status' => 1,
                ]);
                Activity::create([
                    'title' => 'Confirm Batch',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have confirmed Batch: ' . $batch->batch_code,
                ]);
                Alert::success('Success', 'Operation Successfull');
                return back();
            } else {
                Alert::error('Failed', 'Batch is Already Confirmed');
                return back();
            }
        }

        if (Gate::allows('brand_ambassador_access')) {
            $productsTl = Product::where('batch_id', $id)
                ->join('batches', 'batches.id', 'products.batch_id')
                ->where('batches.tl_id_accept', Auth::id())->where('batches.accept_status', 0)->get();
            if (count($productsTl) > 0) {
                $batch = Batch::findOrFail($id);

                $batch->update([
                    'accept_status' => 1,
                ]);
                Activity::create([
                    'title' => 'Confirm Batch',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have confirmed Batch: ' . $batch->batch_code,
                ]);
                Alert::success('Success', 'Operation Successfull');
                return back();
            } else {
                Alert::error('Failed', 'Batch is Already Confirmed');
                return back();
            }
        }
    }

    //Team Leaders Reject Merchandise in batch
    public function rejectBatch(Request $request, $id)
    {

        $productsTl = Product::where('products.batch_id', $id)
            ->join('batches', 'batches.id', 'products.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->where('batches.accept_status', 0)->get();
        if (count($productsTl) > 0) {
            foreach ($productsTl as $product) {
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
            $receiver_phone = $product->user->phone;
            //dd($receiver_email);
            $url_login = URL::to('/login');
            $message = "Hello, Merchandise ($merchandise_type), $productsCount from Batch-Code $batchcode, has been rejected by $sender_email. Kindly Confirm through the portal: $url_login.";
            $details = [
                'title' => 'Mail From ' . $sender_email,
                'body' => $message,
            ];
            //Send Mail
           // Mail::to($receiver_email)->send(new AssignMerchandise($details));
            //Send Sms
            $sms = $this->sendSMSService->sendSMS($message,$receiver_phone);

            Alert::success('Success', 'Operation Successfull. Details has been sent to ' . $receiver_phone);
            return back();
        } else {
            Alert::error('Failed!', 'Products in Batch Have Already Been Confirmed!');
            return back();
        }
    }
}
