@extends('layouts.backend')
@section('content')
@can('team_leader_access')


<div class="card">
    <div class="card-header">
        Brand Ambassador Page
    </div>

    <div class="card-body">
        <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header text-center">
                        <h5>{{ strtoupper($ba->name) }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><b>Email:</b> {{ $ba->email }}</li>
                            <li class="list-group-item"><b>Phone:</b> {{ $ba->phone }}</li>
                            <li class="list-group-item"><b>County:</b>
                                {{ $ba->county->name }} </li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <ul class="list-group">
                            <li class="list-group-item"><b>Total Products:</b> {{ count($products) }}</li>
                            <li class="list-group-item"><b>Total Batches:</b> {{ count($batches) }} :
                                <ul class="list-group">
                                 @foreach ($batches as $batch)
                                    <li class="list-group-item"><strong>Batch Code: </strong>{{ $batch->batch->batch_code }}   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Total: </strong>{{ count(\DB::table('productbas')->where('batch_id',$batch->batch->id)->where('assigned_to', $ba->id)->get())}}  &nbsp;<strong>Products Issued: </strong>{{ count(\DB::table('issue_products')->where('ba_id',$ba->id)->where('batch_id',$batch->batch->id)->get())}}</li>
                            @endforeach
                            </ul>
                        </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endcan

@endsection
