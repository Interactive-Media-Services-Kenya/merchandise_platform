@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
    {{-- Admin and TB Access --}}
    @can('admin_access')
        @can('tb_access')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route('products.create') }}">
                        Add Merchandise
                    </a>
                </div>
            </div>
        @endcan

        <div class="card">
            <div class="card-header">
                Merchandise
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable" id="ProductTableAdmin">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Merchandise Type
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    BarCode
                                </th>
                                @can('tb_access')
                                    <th>
                                        Team Leader
                                    </th>
                                @endcan
                                <th>
                                    Batch Code
                                </th>
                                <th>
                                    Date Added
                                </th>
                                @can('admin_access')
                                    <th>
                                        Actions
                                    </th>
                                @endcan

                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($productsAdmin as $key => $product)
                            <tr data-entry-id="{{ $product->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $product->id ?? '' }}
                                </td>
                                <td>
                                    {{ $product->category->title ?? '' }}
                                </td>
                                <td>
                                    {{ $product->client->name ?? '' }}
                                </td>
                                <td>
                                    {{ $product->product_code ?? '' }}
                                </td>
                                <td>@php
                                    $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                                @endphp
                                    {!! $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128) !!}
                                </td>
                                @can('tb_access')
                                    <td>
                                        {{ $product->assign->email ?? 'Not Assigned' }}
                                    </td>
                                @endcan
                                <td>
                                    {{ $product->batch->batch_code ?? 'Single Product' }}
                                </td>
                                <td>
                                    {{ $product->created_at ?? '' }}
                                </td>
                                @can('admin_access')
                                    <td>
                                        <a href="{{ route('products.edit', [$product->id]) }}"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="{{ route('products.destroyProduct', [$product->id]) }}"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                    </td>
                                @endcan

                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan

    {{-- Admin and TB Access --}}
    @can('tb_access')
        {{-- Statistics Report Data For Merchandises --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise</p>
                            <h4 class="mb-0">{{ count($products) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-default text-sm font-weight-bolder">{{ count($productsIssuedOut) }} Issued Out
                            &nbsp;&nbsp;&nbsp;&nbsp; {{ count($products) - count($productsIssuedOut) }} Remaining Merchandise
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Registered Team Leaders</p>
                            <h4 class="mb-0">{{ count($teamleaders) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-primary text-sm font-weight-bolder">{{ count($teamleadersWithBatches) }} Team
                            Leaders With Batches (Confirmed)</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Clients</p>
                            <h4 class="mb-0">{{ count($clients) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-success text-sm font-weight-bolder">{{ count($clientsWithMerchandise) }} Clients
                            With Products
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise Batches</p>
                            <h4 class="mb-0">{{ count($batches) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-info text-sm font-weight-bolder">{{ count($batchesAccepted) }} Confirmed By Team
                            Leader(s)</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-success" href="{{ route('products.create') }}">
                            Add Merchandise
                        </a> &nbsp; <a class="btn btn-primary text-end" href="{{ route('products.assign.create') }}">
                            Assign Merchandise To Team Leader
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-success" href="{{ route('report.products') }}">
                            Report By Date
                        </a> &nbsp; <a class="btn btn-primary text-end" href="{{ route('report.clients') }}">
                            Report By Client
                        </a>
                        &nbsp; <a class="btn btn-primary text-end" href="{{ route('report.product-type') }}">
                            Report By Merchandise Type
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Merchandise
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Merchandise Type
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    BarCode
                                </th>
                                @can('tb_access')
                                    <th>
                                        Team Leader
                                    </th>
                                @endcan
                                <th>
                                    Batch Code
                                </th>
                                <th>
                                    Date Added
                                </th>
                                @can('admin_access')
                                    <th>
                                        Actions
                                    </th>
                                @endcan

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr data-entry-id="{{ $product->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $product->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->category->title ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->client->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->product_code ?? '' }}
                                    </td>
                                    <td>@php
                                        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                                    @endphp
                                        {!! $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128) !!}
                                    </td>
                                    @can('tb_access')
                                        <td>
                                            {{ $product->assign->email ?? 'Not Assigned' }}
                                        </td>
                                    @endcan
                                    <td>
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
                                    </td>
                                    <td>
                                        {{ $product->created_at ?? '' }}
                                    </td>
                                    @can('admin_access')
                                        <td>
                                            <a href="{{ route('products.edit', [$product->id]) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <a href="{{ route('products.destroyProduct', [$product->id]) }}"
                                                class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                        </td>
                                    @endcan

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan

    {{-- Client Access --}}
    @can('client_access')
        {{-- Statistics Report Data For Merchandises --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise</p>
                            <h4 class="mb-0">{{ count($productsClient) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-default text-sm font-weight-bolder">{{ count($productsIssuedOut) }} Issued Out
                            &nbsp;&nbsp;&nbsp;&nbsp; {{ count($productsClient) - count($productsIssuedOut) }} Remaining
                            Merchandise
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Registered Sales Representatives</p>
                            <h4 class="mb-0">{{ count($salesreps) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-primary text-sm font-weight-bolder">{{ count($teamleadersWithBatches) }} Sales
                            Represantatives With Batches (Confirmed)</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Clients</p>
                            <h4 class="mb-0">{{ count($clients) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-success text-sm font-weight-bolder">{{ count($clientsWithMerchandise) }} Clients
                            With Products
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise Batches</p>
                            <h4 class="mb-0">{{ count($batches) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-info text-sm font-weight-bolder">{{ count($batchesAccepted) }} Confirmed By
                            Sales Representatives</p>
                    </div>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-success" href="{{ route('products.create') }}">
                            Add Merchandise
                        </a> &nbsp; <a class="btn btn-primary text-end" href="{{ route('products.assign.create') }}">
                            Assign Merchandise To Sale Representative
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-success" href="{{ route('report.products.client') }}">
                            Report By Date
                        </a>
                        &nbsp; <a class="btn btn-primary text-end"
                            href="{{ route('report.product-type.client', [Auth::user()->client_id]) }}">
                            Report By Merchandise Type
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Merchandise
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Merchandise Type
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    BarCode
                                </th>
                                @can('tb_access')
                                    <th>
                                        Team Leader
                                    </th>
                                @endcan
                                <th>
                                    Batch Code
                                </th>
                                <th>
                                    Date Added
                                </th>
                                @can('admin_access')
                                    <th>
                                        Actions
                                    </th>
                                @endcan

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr data-entry-id="{{ $product->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $product->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->category->title ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->client->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->product_code ?? '' }}
                                    </td>
                                    <td>@php
                                        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                                    @endphp
                                        {!! $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128) !!}
                                    </td>
                                    @can('tb_access')
                                        <td>
                                            {{ $product->assign->email ?? 'Not Assigned' }}
                                        </td>
                                    @endcan
                                    <td>
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
                                    </td>
                                    <td>
                                        {{ $product->created_at ?? '' }}
                                    </td>
                                    @can('admin_access')
                                        <td>
                                            <a href="{{ route('products.edit', [$product->id]) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <a href="{{ route('products.destroyProduct', [$product->id]) }}"
                                                class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                        </td>
                                    @endcan

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan

    {{-- Team Leader Access --}}
    @can('team_leader_access')
        {{-- Statistics Report Data For Merchandises --}}
        <div class="row mb-4">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise</p>
                            <h4 class="mb-0">{{ count($productsTls) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-default text-sm font-weight-bolder">{{ count($productsIssuedOutTL) }} Assigned
                            &nbsp;&nbsp;&nbsp;&nbsp; {{ count($productsTls) - count($productsIssuedOutTL) }} Remaining
                            Merchandise
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">My Brand Ambassadors</p>
                            <h4 class="mb-0">{{ count($brandAmbassadors) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-primary text-sm font-weight-bolder">Total Registered Brand Ambassadors </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Batches</p>
                            <h4 class="mb-0">{{ count($batchesTl) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-success text-sm font-weight-bolder">Batches Assigned
                        </p>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise Batches</p>
                            <h4 class="mb-0">{{ count($batches) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        <p class="mb-0 text-info text-sm font-weight-bolder">{{ count($batchesAccepted) }} Confirmed By Team
                            Leader(s)</p>
                    </div>
                </div>
            </div> --}}
        </div>
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('products.create') }}">
                    Assign Merchandise
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Merchandise
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>

                                <th>
                                    Merchandise Type
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    BarCode
                                </th>
                                <th>
                                    Asigned To
                                </th>
                                <th>
                                    Date Assigned
                                </th>
                                <th>
                                    Batch Code
                                </th>
                                @can('admin_access')
                                    <th>
                                        Actions
                                    </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productsTls as $product)
                                <tr>
                                    <td>

                                    </td>
                                    <td>
                                        {{ $product->category->title ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->client->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->product_code ?? '' }}
                                    </td>
                                    <td>@php
                                        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                                    @endphp
                                        {!! $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128) !!}
                                    </td>
                                    <td>
                                        {{ $product->productBa->user->email ?? 'Not Assigned' }}
                                    </td>
                                    <td>
                                        {{ $product->productBa->created_at ?? 'Not Assigned' }}
                                    </td>
                                    <td>
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
                                    </td>
                                    @can('admin_access')
                                        <td>
                                            <a href="{{ route('products.edit', [$product->id]) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                            <a href="{{ route('products.destroyProduct', [$product->id]) }}"
                                                class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                        </td>
                                    @endcan

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan

    {{-- Brand Ambassador --}}
    @can('brand_ambassador_access')
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Merchandise</h6>
                    </div>

                    <div class="col-md-6 text-center"><a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                            data-target="#staticBackdropIssueBatch">Issue Out Batch</a>
                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdropIssueBatch" data-backdrop="static" data-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Batch Issue Merchandise
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('products.issue.batch') }}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group mt-4">
                                                <label for="reason">
                                                    <h6>Select Batch</h6>
                                                </label>
                                                <select name="batch_id" class="form-control" style="border:solid 1px;"
                                                    required>
                                                    <option selected disabled value="">Select Batch</option>
                                                    @foreach ($batchesBa as $batch)
                                                        <option value="{{ $batch->id }}">
                                                            {{ strtoupper($batch->batch_code) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mt-4">
                                                <label for="quantity">
                                                    Quantity
                                                </label>
                                                <input type="number" name="quantity" class="form-control"
                                                    style="border:solid 1px;" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Merchandise Type
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    BarCode
                                </th>
                                <th>
                                    Batch Code
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productsBas as $key => $product)
                                <tr data-entry-id="{{ $product->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $product->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->category->title ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->client->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->product_code ?? '' }}
                                    </td>
                                    <td>
                                        @php
                                            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                                        @endphp
                                        {!! $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128) !!}
                                    </td>
                                    <td>
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
                                    </td>
                                    <td><a href="/products/issue/product/{{ $product->id }}/{{ $product->batch->id }}"
                                            class="btn btn-sm btn-warning">Issue Out</a></td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan

@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
        integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ProductTable').DataTable({
                dom: 'lBfrtip',
                pageLength: 100,
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Merchandise_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'merchandise_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#ProductTableAdmin').DataTable({
                processing: true,
                method:'GET',
                serverSide: true,
                ajax: "{{ route('products.index') }}",
                columns: [
                    {
                        data: 'placeholder',
                        name: 'placeholder'
                    },

                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'category',
                        name: 'category.title'
                    },
                    {
                        data: 'client',
                        name: 'client.name'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    {
                        data: 'bar_code',
                        name: 'bar_code'
                    },
                    {
                        data: 'batch',
                        name: 'batch.batch_code'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                dom: 'lBfrtip',
                pageLength: 100,
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Merchandise_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'merchandise_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endsection
