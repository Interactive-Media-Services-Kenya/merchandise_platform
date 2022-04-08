<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardApiResource;
use App\Models\Activity;
use App\Models\Batch;
use App\Models\Category;
use App\Models\IssueProduct;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class DashboardApiController extends Controller
{
    // True Blaq Api Functions


    // Products Issued Out Per Month
    public function productsPerMonth()
    {
        $products = [];
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }
        //dd($months);
        for ($i = 0; $i < count($months); $i++) {
            $monthDates = IssueProduct::whereMonth('created_at', $i + 1)->first();
            if ($monthDates != null) {
                $date = \Carbon\Carbon::parse($monthDates->created_at)->format('F');

                if ($date == $months[$i]) {
                    $count = count(IssueProduct::whereMonth('created_at', $i + 1)->get());
                    $data = [
                        'month' => \Carbon\Carbon::parse($monthDates->created_at)->format('F'),
                        'count' => $count,
                    ];
                    array_push($products, $data);
                } else {
                    $data = [
                        'month' => $months[$i],
                        'count' => 0,
                    ];
                    array_push($products, $data);
                }
            } else {
                $data = [
                    'month' => $months[$i],
                    'count' => 0,
                ];
                array_push($products, $data);
            }
        }


        return new DashboardApiResource($products);
    }

    public function productsPerType()
    {
        $categories = IssueProduct::select('*')->join('products', 'products.id', 'issue_products.product_id')->groupBy('products.category_id')->get();
        $types = [];
        foreach ($categories as $category) {
            array_push($types, $category->category_id);
        }

        $products = [];
        for ($i = 0; $i < count($types); $i++) {
            $product = IssueProduct::select('*')->join('products', 'products.id', 'issue_products.product_id')->where('products.category_id', $types[$i])->get();

            $data = [
                'name' => Category::where('id', $types[$i])->value('title'),
                'count' => count($product),
            ];
            array_push($products, $data);
        }
        return new DashboardApiResource($products);
    }

    public function productsPerTypePerMonth()
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        $productTypeMostIssues = IssueProduct::select('*', DB::raw('count(*) as count_per_type'))
            ->groupBy('category_id')->take(5)
            ->get();

        // dd($productTypeMostIssues);
        //dd($productTypeMostIssues);

        $products = [];
        for ($i = 0; $i < count($months); $i++) {
            $monthDates = IssueProduct::whereMonth('created_at', $i + 1)->first();
            if ($monthDates != null) {
                $data = [
                    'month' => $months[$i],
                ];
                foreach ($productTypeMostIssues as $productTypeMostIssue) {
                    $count = count(IssueProduct::where('category_id', $productTypeMostIssue->category_id)->whereMonth('created_at', $i + 1)->get());
                    $dataItems = [
                        'products' => [
                            'type' => $productTypeMostIssue->category->title,
                            'count' => $count,
                        ],
                    ];
                    array_push($data, $dataItems);
                }
                array_push($products, $data);
            } else {
                $data = [
                    'month' => $months[$i],
                ];
                foreach ($productTypeMostIssues as $productTypeMostIssue) {
                    $dataItems = [
                        'type' => $productTypeMostIssue->category->title,
                        'count' => 0,
                    ];
                    array_push($data, $dataItems);
                }
                array_push($products, $data);
            }
        }

        return new DashboardApiResource($products);
    }
    public function activitiesApi()
    {
        $activities = Activity::with('user')->select('*')->latest()->get();

        return new DashboardApiResource($activities);
    }



    // Client Api Functions


    // Products Issued Out Per Month
    public function productsPerMonthClient($client_id)
    {
        $products = [];
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }
        //dd($months);
        for ($i = 0; $i < count($months); $i++) {
            $monthDates = IssueProduct::whereMonth('created_at', $i + 1)->first();
            if ($monthDates != null) {
                $date = \Carbon\Carbon::parse($monthDates->created_at)->format('F');

                if ($date == $months[$i]) {
                    $batchClient = Batch::join('storages', 'storages.id', 'batches.storage_id')
                        ->where('storages.client_id', $client_id)->select('batches.id')->get();
                    $issuePro = IssueProduct::join('batches', 'batches.id', 'issue_products.batch_id')
                        ->whereIn('issue_products.batch_id', $batchClient)
                        ->whereMonth('issue_products.created_at', $i + 1)
                        ->get();

                    $count = count($issuePro);
                    $data = [
                        'month' => \Carbon\Carbon::parse($monthDates->created_at)->format('F'),
                        'count' => $count,
                    ];
                    array_push($products, $data);
                } else {
                    $data = [
                        'month' => $months[$i],
                        'count' => 0,
                    ];
                    array_push($products, $data);
                }
            } else {
                $data = [
                    'month' => $months[$i],
                    'count' => 0,
                ];
                array_push($products, $data);
            }
        }


        return new DashboardApiResource($products);
    }
    public function productsPerTypeClient($client_id)
    {
        $batchClient = Batch::join('storages', 'storages.id', 'batches.storage_id')
                        ->where('storages.client_id', $client_id)->select('batches.id')->get();
        $categories = IssueProduct::select('*')->whereIn('issue_products.batch_id', $batchClient)->join('products', 'products.id', 'issue_products.product_id')->groupBy('products.category_id')->get();
        $types = [];
        foreach ($categories as $category) {
            array_push($types, $category->category_id);
        }

        $products = [];
        for ($i = 0; $i < count($types); $i++) {
            $product = IssueProduct::select('*')->join('products', 'products.id', 'issue_products.product_id')->where('products.category_id', $types[$i])->get();

            $data = [
                'name' => Category::where('id', $types[$i])->value('title'),
                'count' => count($product),
            ];
            array_push($products, $data);
        }
        return new DashboardApiResource($products);
    }
}
