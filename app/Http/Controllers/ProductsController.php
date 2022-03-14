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
        $products = Product::where('user_id', Auth::id())->get();

        $productsTls = Product::where('assigned_to', Auth::id())->get();

        $productsBas = Product::join('productbas', 'products.id', 'productbas.product_id')->where('productbas.assigned_to', Auth::id())->get();

        return view('products.index', compact('products', 'productsTls', 'productsBas'));
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
                Alert::success('Success', $quantity . ' Merchandises Added Successfully of Batch Code: ' . $batchcode);
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

        //Get all product with the request batch id
        $productBas = Productbas::select('product_id')->where('batch_id', $request->batch_id)->get();
        $productsCount = Product::where('batch_id', $request->batch_id)->whereNotIn('id', $productBas)->get();
        //dd($productBas);
        if ($quantity > 0 && $quantity <= count($productsCount)) {
            $dataProducts = [];
            $products = Product::where('batch_id', $request->batch_id)->whereNotIn('id', $productBas)->take($quantity)->get();
            foreach ($products as $product) {
                $data = [
                    'batch_id' => $request->batch_id,
                    'assigned_to' => $request->assigned_to,
                    'product_id' => $product->id,
                    'created_at' => \Carbon\Carbon::now(),
                ];
                array_push($dataProducts, $data);
            }

            $finaldata = DB::table('productbas')->insert($dataProducts);

            if ($finaldata) {
                $receiver_email = User::where('id', $request->assigned_to)->value('email');
                $details = [
                    'title' => 'Mail from {{Auth::user()->email}}',
                    'body' => 'This is for testing email using smtp'
                ];
                // dd($details);

                Mail::to($receiver_email)->send(new AssignMerchandise($details));
                Alert::success('Success', 'You have Successfully assigned ' . $quantity . ' Merchandise');
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
}
