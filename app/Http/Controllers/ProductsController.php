<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Batch;
use App\Models\Productbas;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AssignMerchandise;
use App\Models\Activity;
use App\Models\IssueProduct;
use App\Models\Reject;
use Illuminate\Support\Facades\URL;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        // ! Products Belonging to a Team Leader
        $productsTls = Product::select('*')->where('assigned_to', Auth::id())
            ->join('batches', 'batches.id', 'products.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->get();
        $issuedProducts = IssueProduct::select('product_id')->where('ba_id', Auth::id())->get();
        $productsBa = Productbas::select('product_id')->where('assigned_to', Auth::id())->get();
        // !Filter Confirmed Product (accept_status) belonging to Auth Brand Ambassador and not issued out
        $productsBas = Product::select('*')->where('accept_status',1)->whereIn('id', $productsBa)->whereNotIn('id', $issuedProducts)->get();


        $batchesBa = Batch::select('*')->whereIn('id', $productsBas)->get();
        //dd($batchesBa);
        return view('products.index', compact('products', 'productsTls', 'productsBas', 'batchesBa'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamleaders = User::where('role_id', 3)->get();
        $clients = Client::all();
        $categories = Category::all();


        $region_id = Auth::user()->county_id;

        $brandAmbassadors =  User::where('role_id', 4)->where('county_id', $region_id)->get();
        $batches = Product::select('batch_id', 'batch_code')->where('assigned_to', Auth::id())->join('batches', 'batches.id', 'products.batch_id')->groupBy('batch_id')->get();
        //dd($batches);

        return view('products.create', compact('teamleaders', 'categories', 'clients', 'brandAmbassadors', 'batches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|integer',
            'category_id' => 'required|integer',
            'assigned_to' => 'required|integer',
        ]);
        $productname = substr(\DB::table('categories')->where('id', $request->category_id)->value('title'), 0, 1);
        $productname = strtoupper($productname);
        $url_login = URL::to('/login');
        if ($request->quantity != null) {
            $quantity = $request->quantity;

            //Generate BatchCode
            $batchcode = $this->generateBatchCode() . $productname;

            //Save Batch Code
            $batch = Batch::create([
                'batch_code' => $batchcode,
                'tl_id_accept' => $request->assigned_to,
                'accept_status' => 0,
            ]);
            //dd($batch->batch_code);
            $merchandises = [];
            //loop through creating products with the same batch code using quantity
            for ($i = 0; $i < $quantity; $i++) {
                //generate productCode
                $product_code = $productname . $this->generateProductCode();

                $data = Product::create([
                    'product_code' => $product_code,
                    'user_id' => Auth::id(),
                    'owner_id' => $request->owner_id,
                    'category_id' => $request->category_id,
                    'client_id' => $request->client_id,
                    'batch_id' => $batch->id,
                    'assigned_to' => $request->assigned_to,
                ]);
                Activity::create([
                    'title' => 'Merchandise Created',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' Added Merchandise:' . $product_code,
                ]);
                if (!$data) {
                    Alert::error('Failed', 'Merchandises Not Added');
                    return back();
                }
                array_push($merchandises, $data);
            }
            if (count($merchandises) == $quantity) {
                $receiver_email = User::where('id', $request->assigned_to)->value('email');
                $sender_email = Auth::user()->email;
                //Add Message for assigning merchandises : includes merchandise type, batch_code quantity
                $merchandise = array_pop($merchandises);
                $merchandise_type = $merchandise->category->title;
                // dd($merchandise_type);
                $message = "Hello, You have been assigned $quantity Merchandises ($merchandise_type) from Batch-Code $batchcode. Kindly Confirm through the portal: $url_login";
                $details = [
                    'title' => 'Mail from ' . $sender_email,
                    'body' => $message,
                ];
                // dd($details);
                Mail::to($receiver_email)->send(new AssignMerchandise($details));
                Alert::success('Success', $quantity . 'Merchandises Added Successfully of Batch Code:' . $batchcode);
                return back();
            } else {
                Alert::error('Failed', 'Merchandises Not Added');
                return back();
            }
        } else {
            # Save Single  Product with no Batch
            $product_code = $productname . $this->generateProductCode();
            $data = Product::create([
                'product_code' => $product_code,
                'user_id' => Auth::id(),
                'owner_id' => $request->owner_id,
                'category_id' => $request->category_id,
                'client_id' => $request->client_id,
                'assigned_to' => $request->assigned_to,
            ]);
            Activity::create([
                'title' => 'Merchandise Created',
                'user_id' => Auth::id(),
                'description' => Auth::user()->name . ' Added Merchandise:' . $product_code,
            ]);
            if ($data) {
                $receiver_email = User::where('id', $request->assigned_to)->value('email');
                $sender_email = Auth::user()->email;
                //Add Message for assigning merchandises : includes merchandise type, batch_code quantity

                $merchandise_type = $data->category->title;

                $message = "Hello, You have been assigned Merchandise ($merchandise_type) of product-code $product_code. Kindly Confirm through the portal: $url_login";
                $details = [
                    'title' => 'Mail from ' . $sender_email,
                    'body' => $message,
                ];

                Mail::to($receiver_email)->send(new AssignMerchandise($details));
                Alert::success('Success', 'Merchandises: ' . $product_code . ' Added Successfully');
                return back();
            } else {
                Alert::error('Failed', 'Merchandise Not Added');
                return back();
            }
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('admin_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product = Product::findOrFail($id);
        $teamleaders = User::where('role_id', 3)->get();
        $clients = Client::all();

        $categories = Category::all();


        return view('products.edit', compact('product', 'teamleaders', 'clients', 'categories'));
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
        $product = Product::findOrFail($id);
        $request->validate([
            'client_id' => 'required|integer',
            'category_id' => 'required|integer',
            'assigned_to' => 'required|integer',
        ]);
        if ($product->update($request->all())) {
            // $product->update([
            //     'user_id' => Auth::id(),
            // ]);
            Activity::create([
                'title' => 'Merchandise Updated',
                'user_id' => Auth::id(),
                'description' => Auth::user()->name . 'Added Merchandise:' . $product->product_code,
            ]);
            Alert::success('Success', 'Merchandise Updated Successfully');
            return back();
        } else {
            Alert::error('Failed', 'Merchandise Not Updated');
            return back();
        }
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

    public function generateProductCode()
    {
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 4);
        $code = mt_rand(1000, 9999) . $permitted_chars;

        return $code;
    }

    public function generateBatchCode()
    {
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $batchcode = 'BAT-' . mt_rand(1000, 9999) . substr(str_shuffle($permitted_chars), 0, 4);

        return $batchcode;
    }
    public function storeBas(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|numeric',
            'assigned_to' => 'required|numeric',
        ]);
        $quantity = $request->quantity;
        $url_login = URL::to('/login');
        //Get all product with the request batch id & Filter unassigned Product
        $productBas = Productbas::select('product_id')->where('batch_id', $request->batch_id)->get();
        $productsCount = Product::where('products.batch_id', $request->batch_id)->whereNotIn('products.id', $productBas)
            ->join('batches', 'batches.id', 'products.batch_id')
            ->where('batches.tl_id_accept', Auth::id())->where('batches.accept_status',1)->get();
        // dd($productBas);
        if ($quantity > 0 && $quantity <= count($productsCount)) {
            $dataProducts = [];
            $products = Product::where('batch_id', $request->batch_id)->whereNotIn('id', $productBas)->take($quantity)->get();

            foreach ($products as $product) {
                $data = Productbas::create([
                    'batch_id' => $request->batch_id,
                    'assigned_to' => $request->assigned_to,
                    'product_id' => $product->id,
                    'created_at' => \Carbon\Carbon::now(),
                ]);
                Activity::create([
                    'title' => 'Assign Merchandise',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have assigned merchandise: ' . $data->product->product_code . ' to ' . $data->user->email,
                ]);
                array_push($dataProducts, $data);
            }


            if (count($dataProducts) > 0) {
                $receiver_email = User::where('id', $request->assigned_to)->value('email');

                $sender_email = Auth::user()->email;
                //Add Message for assigning merchandises : includes merchandise type, batch_code quantity
                $merchandise = array_pop($dataProducts);
                //Get the item type in batch
                $merchandise_type = $merchandise->product->category->title;
                $batchcode = Batch::where('id', $request->batch_id)->value('batch_code');
                $message = "Hello, You have been assigned $quantity Merchandises ($merchandise_type) from Batch-Code $batchcode. Kindly Confirm through the portal: $url_login";
                $details = [
                    'title' => 'Mail from ' . $sender_email,
                    'body' => $message,
                ];

                Mail::to($receiver_email)->send(new AssignMerchandise($details));
                Alert::success('Success', 'Merchandises Assigned Successfully to: ' . $receiver_email);
                return back();
            } else {
                Alert::error('Error', 'Merchandise not Succesfully Assigned');
                return back();
            }
        } else {
            Alert::error('Error', 'Merchndises Quantity exceeds maximum: ' . count($productsCount));
            return back();
        }
    }
    public function reject(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'accept_status' => 0,
        ]);

        $reason = Reject::create([
            'reason_id' => $request->reason_id,
            'user_id' => Auth::id(),
            'description' => $request->description,
            'product_id' => $product->id,
        ]);
        Activity::create([
            'title' => 'Reject Merchandise',
            'user_id' => Auth::id(),
            'description' => Auth::user()->name . ' have rejected merchandise: ' . $product->product_code,
        ]);
        $merchandise_type = $product->category->title;
        $batchcode = $product->batch->batch_code;
        $product_code = $product->product_code;
        $sender_email = Auth::user()->email;
        $receiver_email = $product->assign->email;
        $url_login = URL::to('/login');
        $message = "Hello, Merchandise ($merchandise_type), $product_code from Batch-Code $batchcode, has been rejected by $sender_email. Kindly Confirm through the portal: $url_login.";
        $details = [
            'title' => 'Mail From ' . $sender_email,
            'body' => $message,
        ];

        Mail::to($receiver_email)->send(new AssignMerchandise($details));
        Alert::success('Success', 'Operation Successfull An Email has been sent to ' . $receiver_email);
        return back();
    }
    public function confirm($id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'accept_status' => 1,
        ]);

        if ($product) {
            Activity::create([
                'title' => 'Merchandise Comfirmed',
                'user_id' => Auth::id(),
                'description' => Auth::user()->name . ' have confirm merchandise: ' . $product->product_code,
            ]);
            Alert::success('Success', 'Operation Successfull.');
            return back();
        }
    }
    //Brand Ambassador rejects Merrchandise in batch
    // Confirm multiple products with batch code assigned to the user

    public function confirmBatch($id)
    {
        //Get List of products to be confirmed
        $productaccepted = Product::select('id')->where('batch_id', $id)->where('accept_status', 0)->get();
        $products = Productbas::select('*')->whereIn('product_id', $productaccepted)->where('assigned_to', Auth::id())->get();
        if (count($products) > 0) {
            //Confirm and update individual products in the Batch
            foreach ($products as $product) {
                $product = Product::findOrFail($product->product_id);

                $product->update([
                    'accept_status' => 1,
                ]);
                Activity::create([
                    'title' => 'Merchandise Comfirmed',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have confirm merchandise: ' . $product->product_code,
                ]);
            }
            Alert::success('Success', 'Operation Successfull.');
            return back();
        } else {
            Alert::error('Failed', 'No products in Batch');
            return back();
        }
    }
    //Brand Ambassador rejects Merrchandise in batch
    public function rejectBatch(Request $request, $id)
    {
        $productaccepted = Product::select('id')->where('batch_id', $id)->where('accept_status', 0)->get();
        $products = Productbas::select('*')->whereIn('product_id', $productaccepted)->where('assigned_to', Auth::id())->get();
        // dd($products);\
        if (count($products) > 0) {
            foreach ($products as $product) {
                $product = Product::findOrFail($product->product_id);
                $product->update([
                    'accept_status' => 0,
                ]);
                $reason = Reject::create([
                    'reason_id' => $request->reason_id,
                    'user_id' => Auth::id(),
                    'description' => $request->description,
                    'product_id' => $product->id,
                ]);
                Activity::create([
                    'title' => 'Merchandise Rejected',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have rejected ' . $product->product_code,
                ]);
            }

            $product = $products->first();
            $productsCount = count($products);
            $merchandise_type = $product->product->category->title;
            $batchcode = $product->batch->batch_code;
            $sender_email = Auth::user()->email;
            $receiver_email = $product->product->assign->email;

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
            Alert::error('Failed', 'Batch is Aready Confirmed');
            return back();
        }
    }
    public function issueBatch(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        // Get Products belonging to a brand Ambassador
        $productsBa = Productbas::select('product_id')->where('assigned_to', Auth::id())->get();

        //Get Issued Products By a Brand Ambassodor
        $issuedProducts = IssueProduct::select('product_id')->where('ba_id', Auth::id())->get();
        //Filter Out Products not issued but belongs to the selected batch and the logged in Brand Ambassador
        $productsBas = Product::select('*')->whereIn('id', $productsBa)->where('batch_id', $request->batch_id)->whereNotIn('id', $issuedProducts)->get();
        //Check if Batch issue doesn't exceed the expected amount
        if ($request->quantity <= count($productsBas)) {
            $issuedProducts = IssueProduct::select('product_id')->where('ba_id', Auth::id())->get();
            // dd($issuedProducts);
            $productsBa = Productbas::select('product_id')->where('assigned_to', Auth::id())->get();
            $productsBas = Product::select('*')->whereIn('id', $productsBa)->whereNotIn('id', $issuedProducts)->take($request->quantity)->get();

            foreach ($productsBas as $product) {
                IssueProduct::create([
                    'batch_id' => $request->batch_id,
                    'ba_id' => Auth::id(),
                    'product_id' => $product->id,
                    'category_id' => $product->category->id,
                ]);
                Activity::create([
                    'title' => 'Merchandise Issued',
                    'user_id' => Auth::id(),
                    'description' => Auth::user()->name . ' have issued out ' . $product->product_code,
                ]);
            }
            Alert::success('Success', 'Operation Successfull');
            return back();
        } else {
            Alert::error('Failed', 'Quantity Entered Exceeds Merchandise in Stock. Maximum: ' . count($productsBas));
            return back();
        }
    }

    public function issueProduct($product_id, $batch_id)
    {
        $product = Product::findOrFail($product_id);
        $batch = Batch::findOrFail($batch_id);
        IssueProduct::create([
            'ba_id' => Auth::id(),
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'category_id' => $product->category->id,
        ]);
        Activity::create([
            'title' => 'Merchandise Issued',
            'user_id' => Auth::id(),
            'description' => Auth::user()->name . ' have issued out ' . $product->product_code,
        ]);
        Alert::success('Success', 'Operation Successfull');
        return back();
    }
}
