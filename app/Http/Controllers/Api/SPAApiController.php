<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Batch;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\Color;
use App\Models\Customer;
use App\Models\IssueProduct;
use App\Models\Outlet;
use App\Models\Product;
use App\Models\Productbas;
use App\Models\Reason;
use App\Models\Size;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use DB;

class SPAApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    // ! Confirmation of Barcodes by SuperAdmin/Agency/Client

    public function productConfirmation(Request $request)
    {
        // ? SuperAdmin || Agency || Client Can confirm merchandise
        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 5) {
            $product_id = Product::where('product_code', $request->product_code)->value('id');

            $product = Product::where('id', $product_id)->first();

            if ($product != null) {
                $product->update([
                    'is_confirmed' => 1,
                ]);
                Activity::create([
                    'title' => 'Merchandise Comfirmed',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have confirm merchandise: ' . $product->product_code,
                ]);
                return \Response::json([
                    'message' => "Merchandise Confirmation is Successful",
                    'status' => 1,
                ]);
            } else {
                return \Response::json([
                    'message' => "Failed, Merchandise does not exist",
                    'status' => 0,
                ]);
            }
        } else {
            return \Response::json([
                'message' => "Failed, User Is not Authorized to confirm Merchandise",
                'status' => 0,
            ]);
        }
    }

    // Batch Confirmation  Agency, TLs, and BAs,
    public function batchAccept(Request $request)
    {

        // ? Agency || TeamLeader || BrandAmbassador Can confirm merchandise
        //Batches for Agency
        if (Auth::user()->role_id == 2) {
            $batchConfirmed = Batch::wherebatch_code($request->batch_code)->whereaccept_status(1)->first();
            if ($batchConfirmed) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Batch Is Already Confirmed',
                ]);
            }
            $batch = Batch::wherebatch_code($request->batch_code)->whereaccept_status(0)->first();

            if ($batch == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }

            $batch->update([
                'accept_status' => 1,
            ]);

            //Send A confirmation sms to user
            $phoneNumber = Auth::user()->phone;
            $rejectMessage = 'You have successfully Accepted Merchandise of Batch: ' . $request->batch_code;
            $this->sendSMS($phoneNumber, $rejectMessage);
            //return response message
            return response()->json([
                'status' => 1,
                'message' => 'Batch Confirmation was Successful'
            ]);
        }

        // Batches TeamLeaders
        if (Auth::user()->role_id == 3) {
            $batchConfirmed = DB::table('batch_teamleaders')->wherebatch_code($request->batch_code)->whereaccept_status(1)->whereteam_leader_id(Auth::id())->first();
            if ($batchConfirmed) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Batch Is Already Confirmed',
                ]);
            }
            $batch = DB::table('batch_teamleaders')->wherebatch_code($request->batch_code)->whereaccept_status(0)->whereteam_leader_id(Auth::id())->first();

            if ($batch == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }

            DB::table('batch_teamleaders')->wherebatch_code($request->batch_code)->update(['accept_status' => 1, 'updated_at' => \Carbon\Carbon::now()]);

            //Send A confirmation sms to user
            //Send A confirmation sms to user
            $phoneNumber = Auth::user()->phone;
            $rejectMessage = 'You have successfull Accepted Merchandise of Batch: ' . $request->batch_code;
            $this->sendSMS($phoneNumber, $rejectMessage);
            //return response message
            return response()->json([
                'status' => 1,
                'message' => 'Batch Confirmation was Successful'
            ]);
        }
        // Batches BrandAmbassadors
        if (Auth::user()->role_id == 4) {
            $batchConfirmed = DB::table('batch_brandambassadors')->wherebatch_code($request->batch_code)->whereaccept_status(1)->wherebrand_ambassador_id(Auth::id())->first();
            if ($batchConfirmed) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Batch Is Already Confirmed',
                ]);
            }
            $batch = DB::table('batch_brandambassadors')->wherebatch_code($request->batch_code)->whereaccept_status(0)->wherebrand_ambassador_id(Auth::id())->first();

            if ($batch == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }

            DB::table('batch_brandambassadors')->wherebatch_code($request->batch_code)->update(['accept_status' => 1]);

            //Send A confirmation sms to user
            //Send A confirmation sms to user
            $phoneNumber = Auth::user()->phone;
            $rejectMessage = 'You have successfully Accepted Merchandise of Batch: ' . $request->batch_code;
            $this->sendSMS($phoneNumber, $rejectMessage);
            //return response message
            return response()->json([
                'status' => 1,
                'message' => 'Batch Confirmation was Successful'
            ]);
        }
    }


    //? Get All reject Reasons
    public function rejectReasons()
    {
        $rejectReasons = Reason::all();
        return response()->json(
            $rejectReasons
        );
    }
    public function batchReject(Request $request)
    {

        // ? Agency || TeamLeader || BrandAmbassador Can confirm merchandise
        //Batches for Agency
        if (Auth::user()->role_id == 2) {
            $batchRejected = Batch::wherebatch_code($request->batch_code)->whereaccept_status(2)->first();
            if ($batchRejected) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Batch Is Already Rejected',
                ]);
            }
            $batch = Batch::wherebatch_code($request->batch_code)->where('accept_status', '!=', 1)->first();

            if ($batch == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }

            $batch->update([
                'accept_status' => 2,
            ]);

            //Add the reject + Reason
            DB::table('batch_rejects')->insert([
                'user_id' => Auth::id(),
                'batch_agency_id' => $batch->id,
                'reason_id' => $request->reason_id,
                'description' => $request->description,
                'created_at' => \Carbon\Carbon::now(),
            ]);

            //Send A confirmation sms to user
            $phoneNumber = Auth::user()->phone;
            $rejectMessage = 'You have successfull rejected Merchandise of Batch: ' . $request->batch_code;
            $this->sendSMS($phoneNumber, $rejectMessage);
            //return response message
            return response()->json([
                'status' => 1,
                'message' => 'Batch Reject was Successful'
            ]);
        }

        // Batches TeamLeaders
        if (Auth::user()->role_id == 3) {
            $batchRejected = DB::table('batch_teamleaders')->wherebatch_code($request->batch_code)->wherereject_status(1)->whereteam_leader_id(Auth::id())->first();
            if ($batchRejected) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Batch Is Already Rejected',
                ]);
            }
            $batch = DB::table('batch_teamleaders')->wherebatch_code($request->batch_code)->where('accept_status', '!=', 1)->whereteam_leader_id(Auth::id())->first();

            if ($batch == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }

            DB::table('batch_teamleaders')->wherebatch_code($request->batch_code)->update(['reject_status' => 1]);

            //Add the reject + Reason
            DB::table('batch_rejects')->insert([
                'user_id' => Auth::id(),
                'batch_teamleader_id' => DB::table('batch_teamleaders')->wherebatch_code($request->batch_code)->value('id'),
                'reason_id' => $request->reason_id,
                'description' => $request->description,
                'created_at' => \Carbon\Carbon::now(),
            ]);

            //Send A confirmation sms to user
            //Send A confirmation sms to user
            $phoneNumber = Auth::user()->phone;
            $rejectMessage = 'You have successfull rejected Merchandise of Batch: ' . $request->batch_code;
            $this->sendSMS($phoneNumber, $rejectMessage);
            //return response message
            return response()->json([
                'status' => 1,
                'message' => 'Batch Rejection was Successful'
            ]);
        }
        // Batches BrandAmbassadors
        if (Auth::user()->role_id == 4) {
            $batchRejected = DB::table('batch_brandambassadors')->wherebatch_code($request->batch_code)->wherereject_status(1)->wherebrand_ambassador_id(Auth::id())->first();
            if ($batchRejected) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Batch Is Already Rejected',
                ]);
            }
            $batch = DB::table('batch_brandambassadors')->wherebatch_code($request->batch_code)->where('accept_status', '!=', 1)->wherebrand_ambassador_id(Auth::id())->first();

            if ($batch == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }

            DB::table('batch_brandambassadors')->wherebatch_code($request->batch_code)->update(['reject_status' => 1]);

            //Add the reject + Reason
            DB::table('batch_rejects')->insert([
                'user_id' => Auth::id(),
                'batch_brandambassador_id' => DB::table('batch_brandambassadors')->wherebatch_code($request->batch_code)->value('id'),
                'reason_id' => $request->reason_id,
                'description' => $request->description,
                'created_at' => \Carbon\Carbon::now(),
            ]);

            //Send A confirmation sms to user
            //Send A confirmation sms to user
            $phoneNumber = Auth::user()->phone;
            $rejectMessage = 'You have successfull rejected Merchandise of Batch: ' . $request->batch_code;
            $this->sendSMS($phoneNumber, $rejectMessage);
            //return response message
            return response()->json([
                'status' => 1,
                'message' => 'Batch Rejection was Successful'
            ]);
        }
    }

    // ? Get all batches for Agency, TLs, and BAs,
    public function batches()
    {
        //Batches for Agency
        if (Auth::user()->role_id == 2) {
            $productBatch = Product::whereowner_id(Auth::id())->groupBy('batch_id')->get();
            if ($productBatch->count() == 0) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }
            $data = [];
            foreach ($productBatch as $pb) {
                $batch = Batch::whereid($pb->batch_id)->get();
                foreach ($batch as $bch) {
                    $item = [
                        'batch' => $bch->batch_code,
                        'confirm_status' => $pb->accept_status == 1 ? true : false,
                        'reject_status' => $pb->reject_status == 1 ? true : false,
                        'product_count' => DB::table('products')->wherebatch_tl_id($pb->id)->count(),
                        'merchandise_type' => DB::table('categories')->whereid(Product::wherebatch_ba_id($pb->id)->value('category_id'))->value('title'),
                    ];
                    array_push($data, $item);
                }
            }
            return response()->json($data, 200);
        }
        //Batches for TL
        if (Auth::user()->role_id == 3) {
            $batch = DB::table('batch_teamleaders')->select('*')->whereteam_leader_id(Auth::id())->get();
            if ($batch->count() == 0) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }
            $data = [];
            foreach ($batch as $pb) {
                $item = [
                    'batch' => $pb->batch_code,
                    'confirm_status' => $pb->accept_status == 1 ? true : false,
                    'reject_status' => $pb->reject_status == 1 ? true : false,
                    'product_count' => DB::table('products')->wherebatch_tl_id($pb->id)->count(),
                    'merchandise_type' => DB::table('categories')->whereid(Product::wherebatch_ba_id($pb->id)->value('category_id'))->value('title'),
                ];
                array_push($data, $item);
            }
            //$count = ['batch_count' => DB::table('batch_teamleaders')->select('*')->whereteam_leader_id(Auth::id())->count()];

            // array_push($data, $count);

            return response()->json($data, 200);
        }
        // Batches For BAs
        if (Auth::user()->role_id == 4) {
            $batch = DB::table('batch_brandambassadors')->select('*')->wherebrand_ambassador_id(Auth::id())->get();
            if ($batch->count() == 0) {
                return response()->json([
                    'status' => 0,
                    'message' => 'No Batches Found',
                ]);
            }
            $data = [];
            foreach ($batch as $pb) {
                $item = [
                    'batch' => $pb->batch_code,
                    'confirm_status' => $pb->accept_status == 1 ? true : false,
                    'reject_status' => $pb->reject_status == 1 ? true : false,
                    'product_count' => DB::table('products')->wherebatch_ba_id($pb->id)->count(),
                    'merchandise_type' => DB::table('categories')->whereid(Product::wherebatch_ba_id($pb->id)->value('category_id'))->value('title'),
                ];
                array_push($data, $item);
            }
            // $count = ['batch_count' => DB::table('batch_brandambassadors')->select('*')->wherebrand_ambassador_id(Auth::id())->count()];

            // array_push($data, $count);

            return response()->json($data, 200);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Unauthorised User'
        ]);
    }
    //Brand Ambassadors
    public function IssueProductBA(Request $request)
    {
        //Check if user is Brand Ambassador && product assigned to him/her
        $data = $request->all();
        // return count($productBa);
        abort_if(auth()->user()->role_id != 4, Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_id = Product::select('id')->where('product_code', $request->product_code)->first();
        if ($product_id == null) {
            return \Response::json([
                'message' => "Merchandise Is not Found",
                'status' => 0,
            ]);
        }
        //Check if product is single
        $productSingle = Product::where('product_code', $request->product_code)->where('ba_id', auth()->user()->id)->first();

        if ($productSingle) {
            //Product Is Single
            if ($productSingle->batch_ba_id == null) {
                $product = Product::where('product_code', $request->product_code)->first();

                $issuedProduct = IssueProduct::where('product_id', $product->id)->first();

                if ($issuedProduct) {
                    return \Response::json([
                        'message' => "Merchandise Is Already Issued Out",
                        'status' => 2,
                    ]);
                } else {
                    $batch = $product->batch_ba_id;
                    $issueProduct = IssueProduct::create([
                        'ba_id' => auth()->user()->id,
                        'batch_id' => $batch,
                        'product_id' => $product->id,
                        'category_id' => $product->category->id,
                    ]);
                    // Save Customer Data through Api.
                    // ? Get all the products issued by a logged in BrandAmbassador
                    $productsIssued = IssueProduct::select('product_id')->where('ba_id', auth()->user()->id)->where('category_id', $product->category_id)->get();
                    // ? Fetch the remaining products of the brandAmbassador Assigned to but not issued out.
                    $remainingProducts = Product::where('ba_id', auth()->user()->id)->where('batch_ba_id', $batch)->whereNotIn('id', $productsIssued)->get();
                    //Save customer details alongside issued Product.
                    if ($request->has('customer_phone') || $request->has('customer_name')) {
                        Customer::create([
                            'name' => $data['customer_name'],
                            'phone' => $data['customer_phone'],
                            'product_id' => $product->id,
                        ]);
                    }
                    Activity::create([
                        'title' => 'Merchandise Issued',
                        'user_id' => auth()->user()->id,
                        'description' => auth()->user()->name . ' have issued out ' . $product->product_code,
                    ]);
                    if ($issueProduct) {

                        return \Response::json([
                            'message' => "Merchandise Found and Issued Successfully",
                            'merchandise_type'  => $product->category->title ?? 'No Merchandise Type Registered for the Merchandise',
                            'remaining_items' => $remainingProducts ? count($remainingProducts) : 0,
                            'issued_items' => $productsIssued ? count($productsIssued) : 0,
                            //Status success
                            'status' => 1,
                        ]);
                    } else {
                        return \Response::json([
                            'message' => "Merchandise Not Found",
                            // Status Unsuccessful
                            'status' => 0,
                        ]);
                    }
                }
            } else {
                //Check if the merchandise belongs to a BA and Have confirmed Batch

                $productBa = Product::where('products.ba_id', auth()->user()->id)
                    ->join('batch_brandambassadors', 'batch_brandambassadors.id', 'products.batch_ba_id')
                    ->where('batch_brandambassadors.accept_status', 1)
                    ->count();

                //Check if product is issued Out
                if ($productBa == 0) {
                    return \Response::json([
                        'message' => "Merchandise Does not Belong to Brand Ambassador",
                        'status' => 0,
                    ]);
                }

                $product = Product::where('product_code', $request->product_code)->first();

                $issuedProduct = IssueProduct::where('product_id', $product->id)->first();

                if ($issuedProduct) {
                    return \Response::json([
                        'message' => "Merchandise Is Already Issued Out",
                        'status' => 2,
                    ]);
                } else {
                    $batch = $product->batch_ba_id;
                    $issueProduct = IssueProduct::create([
                        'ba_id' => auth()->user()->id,
                        'batch_id' => $batch,
                        'product_id' => $product->id,
                        'category_id' => $product->category->id,
                    ]);
                    // Save Customer Data through Api.
                    // ? Get all the products issued by a logged in BrandAmbassador
                    $productsIssued = IssueProduct::select('product_id')->where('ba_id', auth()->user()->id)->where('category_id', $product->category_id)->get();
                    // ? Fetch the remaining products of the brandAmbassador Assigned to but not issued out.
                    $remainingProducts = Product::where('ba_id', auth()->user()->id)->where('batch_ba_id', $batch)->whereNotIn('id', $productsIssued)->get();
                    //Save customer details alongside issued Product.
                    if ($request->has('customer_phone') || $request->has('customer_name')) {
                        Customer::create([
                            'name' => $data['customer_name'],
                            'phone' => $data['customer_phone'],
                            'product_id' => $product->id,
                        ]);
                    }
                    Activity::create([
                        'title' => 'Merchandise Issued',
                        'user_id' => auth()->user()->id,
                        'description' => auth()->user()->name . ' have issued out ' . $product->product_code,
                    ]);
                    if ($issueProduct) {

                        return \Response::json([
                            'message' => "Merchandise Found and Issued Successfully",
                            'merchandise_type'  => $product->category->title ?? 'No Merchandise Type Registered for the Merchandise',
                            'remaining_items' => $remainingProducts ? count($remainingProducts) : 0,
                            'issued_items' => $productsIssued ? count($productsIssued) : 0,
                            //Status success
                            'status' => 1,
                        ]);
                    } else {
                        return \Response::json([
                            'message' => "Merchandise Not Found",
                            // Status Unsuccessful
                            'status' => 0,
                        ]);
                    }
                }
            }
        } else {
            return \Response::json([
                'message' => "Merchandise Not Found",
                // Status Unsuccessful
                'status' => 0,
            ]);
        }
    }
    public function issueMerchandise($data)
    {
        $product = Product::where('product_code', $data['product_code'])->first();

        $issuedProduct = IssueProduct::where('product_id', $product->id)->first();

        if ($issuedProduct) {
            return \Response::json([
                'message' => "Merchandise Is Already Issued Out",
                'status' => 2,
            ]);
        } else {
            $batch = $product->batch_ba_id;
            $issueProduct = IssueProduct::create([
                'ba_id' => auth()->user()->id,
                'batch_id' => $batch,
                'product_id' => $product->id,
                'category_id' => $product->category->id,
            ]);
            // Save Customer Data through Api.
            // ? Get all the products issued by a logged in BrandAmbassador
            $productsIssued = IssueProduct::select('product_id')->where('ba_id', auth()->user()->id)->where('category_id', $product->category_id)->get();
            // ? Fetch the remaining products of the brandAmbassador Assigned to but not issued out.
            $remainingProducts = Product::where('ba_id', auth()->user()->id)->where('batch_ba_id', $batch)->whereNotIn('id', $productsIssued)->get();
            //Save customer details alongside issued Product.
            if ($data->has('customer_phone') || $data->has('customer_name')) {
                Customer::create([
                    'name' => $data['customer_name'],
                    'phone' => $data['customer_phone'],
                    'product_id' => $product->id,
                ]);
            }
            Activity::create([
                'title' => 'Merchandise Issued',
                'user_id' => auth()->user()->id,
                'description' => auth()->user()->name . ' have issued out ' . $product->product_code,
            ]);
            if ($issueProduct) {

                return \Response::json([
                    'message' => "Merchandise Found and Issued Successfully",
                    'merchandise_type'  => $product->category->title ?? 'No Merchandise Type Registered for the Merchandise',
                    'remaining_items' => $remainingProducts ? count($remainingProducts) : 0,
                    'issued_items' => $productsIssued ? count($productsIssued) : 0,
                    //Status success
                    'status' => 1,
                ]);
            } else {
                return \Response::json([
                    'message' => "Merchandise Not Found",
                    // Status Unsuccessful
                    'status' => 0,
                ]);
            }
        }
    }


    // ? Get all the outlets registered in the database
    public function outlets()
    {
        $outlets = Outlet::all();

        $data = [];

        foreach ($outlets as $outlet) {
            $outletData = [
                'id' => $outlet->id,
                'outlet_name' => $outlet->name,
                'outlet_code' => $outlet->code,
                'county' => $outlet->county->name,
            ];
            array_push($data, $outletData);
        }
        if (count($data) == 0) {
            return response()->json([
                'message' => "No Registered Outlets",
                'status' => 0,
            ]);
        } else {
            return response()->json($data, 200);
        }
    }
    public function storages()
    {
        $data = Storage::with('client')->get();

        if (count($data) == 0) {
            return response()->json([
                'message' => "No Registered Storages",
                'status' => 0,
            ]);
        } else {
            return response()->json($data);
        }
    }


    public function merchandise_types()
    {
        $categories = Category::select('id', 'title')->get();
        if ($categories->count() == 0) {
            return response()->json([
                'message' => "No Registered Merchandise Types",
                'status' => 0,
            ]);
        } else {
            return response()->json($categories, 200);
        }
    }

    public function client_brands()
    {
        $clientBrands = Client::with('brands')->get();

        return response()->json($clientBrands, 200);
    }
    public function sizes()
    {
        $sizes = Size::all();

        return response()->json($sizes, 200);
    }
    public function colors()
    {
        $colors = Color::all();

        return response()->json($colors, 200);
    }

    public function uploadMerchandise(Request $request)
    {
        if (!Gate::allows('admin_access')) {
            return response()->json([
                'message' => 'User is Not Authorized',
                'status' => 0,
            ]);
        }
       // $data = json_decode($request->getContent(), true);
       $data = $request->all();

        //        $data = $data->data;

        $assignedProductsData = [];
        $uploadedData = [];
        $productCodesInvalid = [];

        //Single Upload
        // if ($data == null) {
        //     $product_code = $request->product_code;
        //     $client_id = $request->client_id;
        //     $category_id = $request->category_id;
        //     $storage_id = $request->storage_id;
        //     $brand_id = $request->brand_id;
        //     $size = $request->size;
        //     $color = $request->color;


        //     $alreadyUploadedCode = DB::table('products')->whereproduct_code($product_code)->value('product_code');
        //     $validCode = DB::table('product_codes')->whereproduct_code($product_code)->where('product_code', '!=', $alreadyUploadedCode)->value('product_code');

        //     if ($validCode == $product_code) {
        //         DB::table('products')->insert([
        //             'product_code' => $product_code,
        //             'client_id' => $client_id,
        //             'category_id' => $category_id,
        //             //'storage_id'=>$storage_id,
        //             'brand_id' => $brand_id,
        //             'size' => $size,
        //             'color' => $color,
        //             'created_at' => \Carbon\Carbon::now(),
        //             'updated_at' => \Carbon\Carbon::now(),
        //         ]);
        //         $product_code = $validCode;
        //     }

        //     $assigned_product = DB::table('products')->where('product_code', $validCode)->first();
        //     //dd($assigned_product->count());
        //     if ($assigned_product != null) {
        //         return response()->json([
        //             'status' => 1,
        //             'message' => 'Merchandise Uploaded Successfully Confirmed!',
        //             // 'message' => $assigned_product,
        //         ]);
        //     } else {
        //         return response()->json([
        //             'status' => 0,
        //             'message' => 'Failed! Merchandise Not Uploaded!',
        //         ]);
        //     }
        // }


        //Multiple Upload
        foreach ($data as $pr) {
            $product_code = $pr['product_code'];
            $client_id = $pr['client_id'];
            $category_id = $pr['category_id'];
            $storage_id = $pr['storage_id'];
            $brand_id = $pr['brand_id'];
            $size = $pr['size'];
            $color = $pr['color'];


            $alreadyUploadedCode = DB::table('products')->whereproduct_code($product_code)->value('product_code');
            $validCode = DB::table('product_codes')->whereproduct_code($product_code)->where('product_code', '!=', $alreadyUploadedCode)->value('product_code');

            if ($validCode == $product_code) {
                DB::table('products')->insert([
                    'product_code' => $product_code,
                    'client_id' => $client_id,
                    'category_id' => $category_id,
                    //'storage_id'=>$storage_id,
                    'brand_id' => $brand_id,
                    'size' => $size,
                    'color' => $color,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
                $product_code = $validCode;
            }

            $assigned_product = DB::table('products')->where('product_code', $validCode)->first();
            //dd($assigned_product->count());
            if ($assigned_product != null) {
                array_push($assignedProductsData, $product_code);
            } else {
                array_push($productCodesInvalid, $product_code);
            }
        }
        return response()->json([
            'status' => 1,
            'uploaded_merchandise' => count($assignedProductsData) . ' Merchandises Found and Uploaded Successfully',
            'failed_merchandise' => [
                'list' => $productCodesInvalid,
                'count' => count($productCodesInvalid),
            ]
        ]);
    }


    //Fetch Products In Batch
    public function batchProducts($batch_code)
    {
        // Batch for Agency
        if (Auth::user()->role_id == 2) {
            $batch = DB::table('batches')->wherebatch_code($batch_code)->value('id');
            $products = Product::select('product_code',)->wherebatch_id($batch)->cursor();
            $batchProducts = [];
            foreach ($products as $product) {
                array_push($batchProducts, $product->product_code);
            }
        }
        //Batch for TeamLeader
        if (Auth::user()->role_id == 3) {
            $batch = DB::table('batch_teamleaders')->wherebatch_code($batch_code)->value('id');
            $products = Product::select('product_code',)->wherebatch_tl_id($batch)->cursor();
            $batchProducts = [];
            foreach ($products as $product) {
                array_push($batchProducts, $product->product_code);
            }
        }
        //Batch for BrandAmbassador
        if (Auth::user()->role_id == 4) {
            $batch = DB::table('batch_brandambassadors')->wherebatch_code($batch_code)->value('id');
            $products = Product::select('product_code',)->wherebatch_ba_id($batch)->cursor();
            $batchProducts = [];
            foreach ($products as $product) {
                array_push($batchProducts, $product->product_code);
            }
        }
        if ($batchProducts != null) {
            return response()->json([
                'status' => 1,
                'products' => $batchProducts,
            ]);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'No Products In Batch'
            ]);
        }
    }


    //FUnction to Send SMS

    public function sendSMS($receiverNumber, $message)
    {

        try {


            $headers = [
                'Cookie: ci_session=ttdhpf95lap45hq8t3h255af90npbb3ql'
            ];

            $encodMessage = rawurlencode($message);

            $url = 'https://3.229.54.57/expresssms/Api/send_bulk_api?action=send-sms&api_key=Snh2SGFQT0dIZmFtcRGU9ZXBlcEQ=&to=' . $receiverNumber . '&from=IMS&sms=' . $encodMessage . '&response=json&unicode=0&bulkbalanceuser=voucher';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true,);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

            $response = curl_exec($ch);
            $res = json_decode($response);
            date_default_timezone_set('Africa/Nairobi');
            $date = date('m/d/Y h:i:s a', time());

            curl_close($ch);
        } catch (\Exception $e) {

            return redirect()->back()->with("error", $e);
        }
    }

    //Unused Upload Data Optimize


    // public function uploadData($product_code,$client_id,$category_id,$storage_id,$brand_id,$size,$color){
    //     $alreadyUploadedCode = DB::table('products')->whereproduct_code($product_code)->value('product_code');
    //         $validCode = DB::table('product_codes')->whereproduct_code($product_code)->where('product_code', '!=', $alreadyUploadedCode)->value('product_code');

    //         if ($validCode == $product_code) {
    //             DB::table('products')->insert([
    //                 'product_code' => $product_code,
    //                 'client_id' => $client_id,
    //                 'category_id' => $category_id,
    //                 //'storage_id'=>$storage_id,
    //                 'brand_id' => $brand_id,
    //                 'size' => $size,
    //                 'color' => $color,
    //                 'created_at' => \Carbon\Carbon::now(),
    //                 'updated_at' => \Carbon\Carbon::now(),
    //             ]);

    //         }
    //         //Check merchandise is not duplicate
    //         $assigned_product = DB::table('products')->where('product_code', $validCode)->first();
    //         //dd($assigned_product->count());
    //         if ($assigned_product != null) {
    //             array_push($assignedProductsData, $product_code);
    //         } else {
    //             array_push($productCodesInvalid, $product_code);
    //         }
    //         return response()->json([
    //             'status' => 1,
    //             'uploaded_merchandise' => count($assignedProductsData) . ' Merchandises Found and Uploaded Successfully',
    //             'failed_merchandise' => [
    //                 'list' => $productCodesInvalid,
    //                 'count' => count($productCodesInvalid),
    //             ]
    //         ]);
    // }
}
