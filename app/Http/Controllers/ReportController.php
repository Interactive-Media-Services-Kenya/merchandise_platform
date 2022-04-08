<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Client;
use App\Models\IssueProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
    public function products(Request $request)
    {

        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $model = IssueProduct::whereBetween('issue_products.created_at', [$request->from_date, $request->to_date])
                    ->with(['brandambassador', 'product', 'batch', 'category'])->select('issue_products.*');
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
            } else {
                $model = IssueProduct::with(['brandambassador', 'product', 'batch', 'category'])->select('issue_products.*');

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

    public function productsClient(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $batchClient = Batch::join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', Auth::user()->client_id)->select('batches.id')->get();
                $model = IssueProduct::join('batches', 'batches.id', 'issue_products.batch_id')
                    ->whereIn('issue_products.batch_id', $batchClient)
                    ->whereBetween('issue_products.created_at', [$request->from_date, $request->to_date])
                    ->with(['brandambassador', 'product', 'batch', 'category'])->select('issue_products.*');
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
            } else {
                $batchClient = Batch::join('storages', 'batches.storage_id', 'storages.id')->where('storages.client_id', Auth::user()->client_id)->select('batches.id')->get();
                $model = IssueProduct::join('batches', 'batches.id', 'issue_products.batch_id')
                    ->whereIn('issue_products.batch_id', $batchClient)
                    ->with(['brandambassador', 'product', 'batch', 'category'])->select('issue_products.*');

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

        return view('reports.product-report-client');
    }

    public function clients(Request $request)
    {
        $clients = Client::all();
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $model = IssueProduct::join('products', 'products.id', 'issue_products.product_id')
                    ->where('products.client_id', $request->client_id)->whereBetween('issue_products.created_at', [$request->from_date, $request->to_date])
                    ->with(['brandambassador', 'product', 'product.client', 'batch', 'category'])->select('issue_products.*');
                return DataTables::eloquent($model)

                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('batch', function (IssueProduct $product) {

                        return $product->batch->batch_code;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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
            } else {
                $model = IssueProduct::with(['brandambassador', 'product', 'product.client', 'batch', 'category'])->select('issue_products.*');

                return DataTables::eloquent($model)

                    ->addColumn('product_code', function (IssueProduct $product) {

                        return $product->product->product_code;
                    })
                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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

        return view('reports.clients-report', compact('clients'));
    }


    public function productTypes(Request $request)
    {
        $clients = Client::all();
        $categories = Category::all();
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $model = IssueProduct::join('products', 'products.id', 'issue_products.product_id')
                    ->where('products.client_id', $request->client_id)->where('products.category_id', $request->category_id)->whereBetween('issue_products.created_at', [$request->from_date, $request->to_date])
                    ->with(['brandambassador', 'product', 'product.client', 'batch', 'category'])->select('issue_products.*');
                return DataTables::eloquent($model)

                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('batch', function (IssueProduct $product) {

                        return $product->batch->batch_code;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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
            } else {
                $model = IssueProduct::with(['brandambassador', 'product', 'product.client', 'batch', 'category'])->select('issue_products.*');

                return DataTables::eloquent($model)

                    ->addColumn('product_code', function (IssueProduct $product) {

                        return $product->product->product_code;
                    })
                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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

        return view('reports.merchandise-type-report', compact('clients', 'categories'));
    }
    public function productTypesClient(Request $request)
    {

        $categories = Category::where('client_id', Auth::user()->client_id)->get();
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $model = IssueProduct::join('products', 'products.id', 'issue_products.product_id')
                    ->where('products.client_id', Auth::user()->client_id)->where('products.category_id', $request->category_id)
                    ->whereBetween('issue_products.created_at', [$request->from_date, $request->to_date])
                    ->with(['brandambassador', 'product', 'product.client', 'batch', 'category'])->select('issue_products.*');
                return DataTables::eloquent($model)

                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('batch', function (IssueProduct $product) {

                        return $product->batch->batch_code;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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
            } else {
                $model = IssueProduct::with(['brandambassador', 'product', 'product.client', 'batch', 'category'])
                    ->join('products', 'products.id', 'issue_products.product_id')
                    ->where('products.client_id', Auth::user()->client_id)->select('issue_products.*');

                return DataTables::eloquent($model)

                    ->addColumn('product_code', function (IssueProduct $product) {

                        return $product->product->product_code;
                    })
                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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

        return view('reports.merchandise-type-report-client', compact('categories'));
    }

    public function teamleaders(Request $request)
    {
        $teamleaders = User::where('role_id', 3)->get();
        $clients = Client::all();
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $model = IssueProduct::join('products', 'products.id', 'issue_products.product_id')
                    ->where('products.assigned_to', $request->user_id)->where('products.client_id', $request->client_id)->whereBetween('issue_products.created_at', [$request->from_date, $request->to_date])
                    ->with(['brandambassador', 'product', 'product.client', 'product.assign', 'batch', 'category'])->select('issue_products.*');
                return DataTables::eloquent($model)

                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('teamleader', function (IssueProduct $product) {

                        return $product->product->assign->email;
                    })
                    ->addColumn('batch', function (IssueProduct $product) {

                        return $product->batch->batch_code;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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
            } else {
                $model = IssueProduct::with(['brandambassador', 'product', 'product.client', 'product.assign', 'batch', 'category'])->select('issue_products.*');
                return DataTables::eloquent($model)

                    ->addColumn('ba', function (IssueProduct $product) {

                        return $product->brandambassador->email;
                    })
                    ->addColumn('teamleader', function (IssueProduct $product) {

                        return $product->product->assign->email;
                    })
                    ->addColumn('batch', function (IssueProduct $product) {

                        return $product->batch->batch_code;
                    })
                    ->addColumn('client', function (IssueProduct $product) {

                        return $product->product->client->name;
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
            }
        }

        return view('reports.teamleaders-report', compact('teamleaders', 'clients'));
    }
}
