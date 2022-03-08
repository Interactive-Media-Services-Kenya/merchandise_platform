@extends('layouts.backend')
@section('content')
@can('admin_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('products.create') }}">
                Add Merchandise
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        Merchandise
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-product">
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
                            Team Leader
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
                    @foreach($products as $key => $product)
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
                                {{ $product->assign->email ?? '' }}
                            </td>
                            <td>
                                {{ $product->batch->batch_code?? 'Single Product'}}
                            </td>
                            <td>
                                <a href="{{route('products.edit', [$product->id])}}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{route('products.destroyproduct',[$product->id])}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endcan

@endsection
