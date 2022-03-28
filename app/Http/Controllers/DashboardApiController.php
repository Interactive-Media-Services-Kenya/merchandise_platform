<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardApiResource;
use App\Models\Activity;
use App\Models\Category;
use App\Models\IssueProduct;
use Illuminate\Http\Request;
use DB;

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
                // dd($months[$i]);
                if (array_search($date, $months)) {
                    $count = count(IssueProduct::whereMonth('created_at', $i + 1)->get());
                    $data = [
                        'month' => \Carbon\Carbon::parse($monthDates->created_at)->format('F'),
                        'count' => $count,
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
        // ? Create the months January to December
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $months[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }
        // ! Get the top 5 mostly issued product by brand Ambassodors.
        $productTypeMostIssues = IssueProduct::select('*', DB::raw('count(*) as count_per_type'))
            ->groupBy('category_id')->take(5)
            ->get();

        // dd($productTypeMostIssues);
        //dd($productTypeMostIssues);

        $products = [];
        for ($i = 0; $i < count($months); $i++) {
            // ? Fetch the month dates for issued products from the database
            $monthDates = IssueProduct::whereMonth('created_at', $i + 1)->first();
            // ? Set the month if no data is found for the specified month
            if ($monthDates != null) {
                $data = [
                    'month' => $months[$i],
                ];
                // ? Set the default values (count) for each item category to zero
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
                // ? Set the month and count for each category if data is found for the specified month
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
    public function activitiesApi(){
        $activities = Activity::with('user')->select('*')->latest()->get();

        return new DashboardApiResource($activities);
    }
}
