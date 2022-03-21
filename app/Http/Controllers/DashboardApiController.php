<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardApiResource;
use App\Models\Category;
use App\Models\IssueProduct;
use Illuminate\Http\Request;

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
        for ($i=0; $i < count($months); $i++) {
            $monthDates = IssueProduct::whereMonth('created_at', $i+1)->first();

            if ($monthDates != null) {
                $date = \Carbon\Carbon::parse($monthDates->created_at)->format('F');
               // dd($months[$i]);
                if (array_search($date, $months)) {
                    $count = count(IssueProduct::whereMonth('created_at', $i+1)->get());
                    $data = [
                        'month' => \Carbon\Carbon::parse($monthDates->created_at)->format('F'),
                        'count' => $count,
                    ];
                    array_push($products, $data);
                }
            }
            else {
                $data = [
                    'month' => $months[$i],
                    'count' => 0,
                ];
                array_push($products, $data);
            }
        }


        return new DashboardApiResource($products);
    }

    public function productsPerType(){
        $categories = IssueProduct::select('*')->join('products','products.id','issue_products.product_id')->groupBy('category_id')->get();
        $types = [];
        foreach ($categories as $category) {
            array_push($types,$category->category_id);
        }

        $products = [];
        for ($i=0; $i < count($types); $i++) {
            $product = IssueProduct::select('*')->join('products','products.id','issue_products.product_id')->where('products.category_id',$types[$i])->get();

            $data = [
                'name'=> Category::where('id',$types[$i])->value('title'),
                'count' => count($product),
            ];
            array_push($products,$data);
        }
        return new DashboardApiResource($products);
    }
}
