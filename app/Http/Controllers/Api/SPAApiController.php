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
use App\Models\Size;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
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

    //Brand Ambassadors
    public function IssueProductBA(Request $request)
    {
        //Check if user is Brand Ambassador && product assigned to him/her

        // return count($productBa);
        abort_if(auth()->user()->role_id != 4, Response::HTTP_FORBIDDEN, '403 Forbidden');
        $product_id = Product::select('id')->where('product_code', $request->product_code)->first();
        if ($product_id == null) {
            return \Response::json([
                'message' => "Merchandise Is not Found",
                'status' => 0,
            ]);
        }
        $productBa = Productbas::where('assigned_to', auth()->user()->id)->whereIn('product_id', $product_id)->get();
        //Check if product is issued Out
        if (count($productBa) == 0) {
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
            $batch = $product->batch_id;
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
            $remainingProducts = Productbas::where('assigned_to', auth()->user()->id)->whereNotIn('product_id', $productsIssued)->get();
            //Save customer details alongside issued Product.
            if ($request->has('customer_phone') || $request->has('customer_name')) {
                Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
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

        // $data = [];

        // foreach ($storages as $storage) {
        //     $storageData = [
        //         'id' => $storage->id,
        //         'name' => $storage->title,
        //         'client' => $storage->client->name??'',
        //     ];
        //     array_push($data, $storageData);
        // }
        if (count($data) == 0) {
            return response()->json([
                'message' => "No Registered Storages",
                'status' => 0,
            ]);
        } else {
            return response()->json($data, 200);
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
        $data = json_decode($request->getContent(), true);

       // $productsData = $data->data;

        $assignedProductsData = [];
        $uploadedData = [];
        foreach ($data as $pr) {
            $product_code = $pr['product_code'];
            $client_id = $pr['client_id'];
            $category_id = $pr['category_id'];
            $storage_id = $pr['storage_id'];
            $brand_id = $pr['brand_id'];
            $size = $pr['size'];
            $color = $pr['color'];


            $assigned_product = DB::table('products')->where('product_code', $product_code)->first();
        //dd($assigned_product->count());
            if ($assigned_product != null) {
                array_push($assigned_product->product_code,$assignedProductsData);
            }


        // if ($assigned_product->count() != 0) {
        //     return response()->json([
        //         'message' => "Product Code Is already Uploaded",
        //         'status' => 0,
        //     ]);
        // }
        // $product = DB::table('product_codes')->where('product_code', $product_code)->get();

        // if ($product != null) {
        //     $product_upload = Product::where('product_code', null)->first();

        //     if ($product_upload != null) {
        //         $product_upload->update([
        //             'product_code' => $product_code,
        //             'category_id'=> $category_id,
        //             'client_id' => $client_id,
        //             'brand_id' => $brand_id,
        //         ]);
        //         $batch = Batch::where('id',$product_upload->batch_id)->first();
        //         $batch->update([
        //             'storage_id' => $request->storage_id,
        //             'size' => $size,
        //             'color' => $color,
        //         ]);
        //         Activity::create([
        //             'title' => 'Merchandise Uploaded',
        //             'user_id' => Auth::id(),
        //             'description' => Auth::user()->name . ' have uploaded merchandise: ' . $product_code,
        //         ]);
        //     //    array_push($product_upload,)
        //     } else {
        //         return response()->json([
        //             'message' => "Product Code is not Uploaded",
        //             'status' => 0,
        //         ]);
        //     }
        // } else {
        //     return response()->json([
        //         'message' => "Product Code does not exist",
        //         'status' => 0,
        //     ]);
        // }

        }
        return $assignedProductsData;
    }
}
