<?php

namespace App\Http\Controllers;

use App\Models\IssueProduct;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
    public function products(Request $request)
    {

        if (request()->ajax()) {
            if (!empty($request->from_date)) {
                $model = IssueProduct::whereBetween('issue_products.created_at', [$request->from_date, $request->to_date])->with(['brandambassador', 'product', 'batch', 'category']);
                return DataTables::eloquent($model)

                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('batch', function (IssueProduct $product) {

                        return $product->batch->batch_code;
                    })
                    ->addColumn('product_code', function (IssueProduct $product) {

                        return $product->product->product_code;
                    })
                    ->addColumn('category', function (IssueProduct $product) {

                        return $product->category->title;
                    })
                    ->editColumn('created_at', function (IssueProduct $product) {

                        return $product->created_at;
                    })
                    ->toJson();
            }else{
                $model = IssueProduct::with(['brandambassador', 'product', 'batch', 'category']);

                return DataTables::eloquent($model)

                    ->addColumn('product_code', function (IssueProduct $product) {

                        return $product->product->product_code;
                    })
                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('batch', function (IssueProduct $product) {

                        return $product->batch->batch_code;
                    })
                    ->addColumn('category', function (IssueProduct $product) {

                        return $product->category->title;
                    })

                    ->toJson();
            }
        }

        return view('reports.products-report');
    }
}
