@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
    @can('brand_ambassador_access')
        <div class="card">
            <div class="card-header">
                Product
            </div>

            <div class="card-body">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Merchandise-Code
                                : {{ strtoupper($product->product_code) }}</h5>
                        </div>
                        <div class="card-body ">
                            <div class="col-md-4 mx-auto">
                                <p> <b>Merchandise Type: </b> {{ $product->category->title?? 'Type Not Defined' }}</p>
                                <p> <b>Date Received: </b> {{ \DB::table('batch_brandambassadors')->whereid($product->batch_ba_id)->value('created_at')?? 'Type Not Defined' }}</p>
                                <p> <b>Date Issued: </b> {{ $product->issueProduct->created_at?? 'Type Not Defined' }}</p>
                                <p> <b>Size: </b> {{ $product->sizeProduct->name?? 'Size Not Defined' }}</p>
                                <p> <b>Color: </b> {{ $product->colorProduct->name?? 'Color Not Defined' }}</p>
                                <p> <b>Brand: </b> {{ $product->brand->name?? 'Brand Not Defined' }}</p>
                                <p> <b>Campaign: </b> {{ $product->campaign->name?? 'Not Assigned To Campaign' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

