@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <style>
        .input {
            border: 1px solid;
            border-radius: 10px;
        }

    </style>
@section('content')
@can('admin_access')
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Upload Merchandise</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.upload-merchandise') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select
                                    Type</label>

                                <div class="col-md-6">
                                    <select name="category_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Merchandise Type ---</option>
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}">{{ strtoupper($category->title) }}</option>
                                        @empty
                                            <option disabled> No Merchandise To Select Yet</option>
                                        @endforelse
                                    </select>
                                    <input type="hidden" name="owner_id" value="{{Auth::id()}}">
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="brand_id" class="col-md-4 col-form-label text-md-end">Select
                                    Client</label>

                                <div class="col-md-6">
                                    <select name="client_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Client  ---</option>
                                        @forelse ($clients as $client)
                                            <option value="{{ $client->id }}">{{ strtoupper($client->name) }}</option>
                                        @empty
                                            <option disabled> No Brand To Select Yet</option>
                                        @endforelse
                                    </select>
                                    @error('client_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="brand_id" class="col-md-4 col-form-label text-md-end">Select
                                    Brand</label>

                                <div class="col-md-6">
                                    <select name="brand_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Merchandise Brand ---</option>
                                        @forelse ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ strtoupper($brand->name) }}---{{$brand->client->name}}</option>
                                        @empty
                                            <option disabled> No Brand To Select Yet</option>
                                        @endforelse
                                    </select>
                                    @error('brand_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="color" class="col-md-4 col-form-label text-md-end">Merchandise Code</label>

                                <div class="col-md-6">
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="code" value="{{ old('code') }}" autocomplete="code" autofocus placeholder="123456XXX">

                                    @error('code')
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
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
    <script>
        $("#batch").hide();
        $("#quantity").val("");
        $("input[name='flexRadioDefault']").click(function() {
            var status = $(this).val();
            if (status == 2) {
                $("#batch").show();
            } else {
                $("#batch").hide();
                $("#quantity").val("");
            }
        });
    </script>
@endsection
