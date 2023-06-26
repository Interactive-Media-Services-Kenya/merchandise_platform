<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\PermissionsService;

class ProductCodeController extends Controller
{
    protected $permissionsService;

    public function __construct(PermissionsService $permissionsService)
    {
        $this->permissionsService = $permissionsService;
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $productCodesUsed = Product::select('product_code')->where('product_code', '!=', null);
            $query = ProductCode::select('*')->orderBy('id', 'DESC')->whereNotIn('product_code', $productCodesUsed);
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('product_code', function ($row) {
                return $row->product_code ? $row->product_code : '';
            });

            $table->editColumn('bar_code', function ($row) {
                $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();
                return  $generator->getBarcode($row->product_code, $generator::TYPE_CODE_128);
            });


            $table->rawColumns(['placeholder', 'id', 'product_code', 'bar_code']);

            return $table->make(true);
        }

        return view('products.product_codes');
    }

    public function create()
    {
        $permissionName = 'Generate MerchandiseCodes';
        $permissions = $this->permissionsService->getPermissions($permissionName);

        abort_unless($permissions, 403);
        return view('products.create_product_codes');
    }

    public function store(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|max:10000',
        ]);

        $time = \Carbon\Carbon::now();
        $data = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $product_code = $this->generateProductsCode();
            $data[] = [
                'product_code' => $product_code,
                'created_at' => $time
            ];
        }
        //Insert data into database
        \DB::table('product_codes')->insert($data);

        if (count($data) == $request->quantity) {
            Alert::success('Success', 'Merchandise Code Generation was Not Successful');
            return back();
        } else {
            Alert::error('Failed', 'Merchandise Code Generation was Not Successful');
            return back();
        }
    }
    public function generateProductsCode()
    {
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permitted_chars = substr(str_shuffle($permitted_chars), 0, 6);
        $code = mt_rand(100000, 999999) . $permitted_chars;

        return $code;
    }
}
