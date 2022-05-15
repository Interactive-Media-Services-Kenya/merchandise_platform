<?php

namespace App\Http\Controllers;

use App\Models\ProductCode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductCodeController extends Controller
{
    public function index(Request $request){

        if ($request->ajax()) {
            $query = ProductCode::select('*');
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
}
