@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <style>
        .input {
            border: 1px solid;
            border-radius: 10px;
        }

    </style>
@endsection
@section('content')
    @can('admin_access')
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Edit Brand : {{strtoupper($brand->name)}}</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('brands.update',[$brand->id]) }}">
                            @csrf
                            @method('put')
                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end">Brand Name</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="name" required
                                        autocomplete="name" value="{{$brand->name}}">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="client_id" class="col-md-4 col-form-label text-md-end">Select Client</label>

                                <div class="col-md-6">
                                    <select name="client_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected value="{{$brand->client_id}}">{{strtoupper($brand->client->name)}}</option>
                                        @forelse ($clients as $client)
                                            <option value="{{ $client->id }}">{{ strtoupper($client->name) }}</option>
                                        @empty
                                            <option disabled> No Client Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('client_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- End of registered Users --}}
        </div>
    @endcan
@endsection
