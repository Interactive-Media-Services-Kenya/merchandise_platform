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
                        <h4 class="text-center">Add Merchandise</h4>
                    </div>

                    <div class="card-body">
                        {{-- <form method="POST" action="{{ route('products.store') }}"> --}}
                        <form id="form-merchandise">
                            <div class="row mb-3">
                                <label for="category_id" class="col-md-4 col-form-label text-md-end">Select
                                    Type</label>

                                <div class="col-md-6">
                                    <select name="category_id" id="category_id" class="form-control"
                                        style="border: 1px solid; border-radius:10px;" required>
                                        <option selected disabled>--- Select Merchandise Type ---</option>
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}">{{ strtoupper($category->title) }}</option>
                                        @empty
                                            <option disabled> No Merchandise To Select Yet</option>
                                        @endforelse
                                    </select>
                                    <input type="hidden" name="owner_id" value="{{ Auth::id() }}">
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="client_id" class="col-md-4 col-form-label text-md-end">Select
                                    Client</label>

                                <div class="col-md-6">
                                    <select name="client_id" id="client_id" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Client ---</option>
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
                                    <select name="brand_id" id="brand_id" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Merchandise Brand ---</option>
                                        @forelse ($brands as $brand)
                                            <option value="{{ $brand->id }}">
                                                {{ strtoupper($brand->name) }}---{{ $brand->client->name }}</option>
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
                                <label for="color" class="col-md-4 col-form-label text-md-end">Select
                                    Color</label>

                                <div class="col-md-6">
                                    <select name="color" id="color" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Color ---</option>
                                        @forelse ($colors as  $color)
                                            <option value="{{ $color->id }}">{{ strtoupper($color->name) }}</option>
                                        @empty
                                            <option disabled> No Sizes Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="size" class="col-md-4 col-form-label text-md-end">Select
                                    Size</label>

                                <div class="col-md-6">
                                    <select name="size" id="size" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Size ---</option>
                                        @forelse ($sizes as  $size)
                                            <option value="{{ $size->id }}">{{ strtoupper($size->name) }}</option>
                                        @empty
                                            <option disabled> No Sizes Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('size')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select
                                    Storage</label>

                                <div class="col-md-6">
                                    <select name="storage_id" id="storage_id" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Storage ---</option>
                                        @forelse ($storages as  $storage)
                                            <option value="{{ $storage->id }}">{{ strtoupper($storage->title) }} --
                                                {{ $storage->client_id ? $storage->client->name : 'General Store' }}</option>
                                        @empty
                                            <option disabled> No Storage Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('storage_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="product_code" class="col-md-4 col-form-label text-md-end">Merchandise Code</label>

                                <div class="col-md-6">
                                    <input id="product_code" type="text"
                                        class="form-control @error('product_code') is-invalid @enderror  input"
                                        style="border: 1px solid; border-radius:10px;" name="product_code"
                                        value="{{ old('product_code') }}" autocomplete="product_code" autofocus
                                        placeholder="XXXXXXXXXXXXXX" required>

                                    <span class="invalid-feedback" role="alert" id="invalidProductCode">
                                        <strong>Invalid Merchandise Code</strong>
                                    </span>
                                    <span class="invalid-feedback" role="alert" id="isAssigned">
                                        <strong>Merchandise Code is Already Uploaded</strong>
                                    </span>
                                    <span class="text-success" role="alert" id="isSuccess">
                                        <strong>Merchandise Code Uploaded Successfully</strong>
                                    </span>
                                    @error('product_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4" id="submitQuery">
                                    <button type="submit" class="btn btn-success">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('tb_access')
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Add Merchandise</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.store') }}">
                            @csrf


                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select
                                    Type</label>

                                <div class="col-md-6">
                                    <select name="category_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Merchandise Type ---</option>
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}">{{ strtoupper($category->title) }}
                                            </option>
                                        @empty
                                            <option disabled> No Merchandise To Select Yet</option>
                                        @endforelse
                                    </select>
                                    <input type="hidden" name="owner_id" value="{{ Auth::id() }}">
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select
                                    Client</label>

                                <div class="col-md-6">
                                    <select name="client_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Client ---</option>
                                        @forelse ($clients as  $client)
                                            <option value="{{ $client->id }}">{{ strtoupper($client->name) }}</option>
                                        @empty
                                            <option disabled> No Client Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('county_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select
                                    Storage</label>

                                <div class="col-md-6">
                                    <select name="storage_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Storage ---</option>
                                        @forelse ($storages as  $storage)
                                            <option value="{{ $storage->id }}">{{ strtoupper($storage->title) }}</option>
                                        @empty
                                            <option disabled> No Storage Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('storage_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 mx-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Single Merchandise
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault1" value="2">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Batch Merchandise
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="row mb-3" id="batch">
                                <label for="quantity" class="col-md-4 col-form-label text-md-end">Quantity</label>

                                <div class="col-md-6">
                                    <input id="quantity" type="number"
                                        class="form-control @error('quantity') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="quantity" autocomplete="number"
                                        placeholder="200">

                                    @error('quantity')
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
    @can('client_access')
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Add Merchandise</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.store') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select
                                    Type</label>

                                <div class="col-md-6">
                                    <select name="category_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Merchandise Type ---</option>
                                        @forelse ($categoriesClient as $category)
                                            <option value="{{ $category->id }}">{{ strtoupper($category->title) }}
                                            </option>
                                        @empty
                                            <option disabled> No Merchandise To Select Yet</option>
                                        @endforelse
                                    </select>
                                    <input type="hidden" name="owner_id" value="{{ Auth::id() }}">
                                    @error('category_id')
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
                                        @forelse ($brandsClient as $brand)
                                            <option value="{{ $brand->id }}">{{ strtoupper($brand->name) }}</option>
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
                                <label for="color" class="col-md-4 col-form-label text-md-end">Merchandise Color</label>

                                <div class="col-md-6">
                                    <input id="color" type="text"
                                        class="form-control @error('color') is-invalid @enderror  input"
                                        style="border: 1px solid; border-radius:10px;" name="color"
                                        value="{{ old('color') }}" autocomplete="color" autofocus
                                        placeholder="Red,yelloe,green,blue">

                                    @error('color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="size" class="col-md-4 col-form-label text-md-end">Merchandise Size</label>

                                <div class="col-md-6">
                                    <input id="size" type="text"
                                        class="form-control @error('size') is-invalid @enderror  input"
                                        style="border: 1px solid; border-radius:10px;" name="size" value="{{ old('size') }}"
                                        autocomplete="size" autofocus placeholder="S,M,L,XL">

                                    @error('size')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select
                                    Storage</label>

                                <div class="col-md-6">
                                    <select name="storage_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Storage ---</option>
                                        @forelse ($storagesClient as  $storage)
                                            <option value="{{ $storage->id }}">{{ strtoupper($storage->title) }}</option>
                                        @empty
                                            <option disabled> No Storage Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('storage_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 mx-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault2" checked>
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Single Merchandise
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="flexRadioDefault1" value="2">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Batch Merchandise
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="row mb-3" id="batch">
                                <label for="quantity" class="col-md-4 col-form-label text-md-end">Quantity</label>

                                <div class="col-md-6">
                                    <input id="quantity" type="number"
                                        class="form-control @error('quantity') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="quantity" autocomplete="number"
                                        placeholder="200">

                                    @error('quantity')
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
    @can('team_leader_access')
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Assign Merchandise To Brand Ambassador</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.storebas') }}">
                            @csrf


                            <div class="row mb-3">
                                <label for="batch_id" class="col-md-4 col-form-label text-md-end">Select
                                    Merchandise Batch</label>

                                <div class="col-md-6">
                                    <select name="batch_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Merchandise Batch ---</option>
                                        @forelse ($batches as $batch)
                                            <option value="{{ $batch->batch_id }}">{{ strtoupper($batch->batch_code) }}
                                            </option>
                                        @empty
                                            <option disabled> No Merchandise To Select Yet</option>
                                        @endforelse
                                    </select>
                                    <input type="hidden" name="owner_id" value="{{ Auth::id() }}">

                                    @error('batch_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="assigned_to" class="col-md-4 col-form-label text-md-end">Select Brand
                                    Ambassador</label>

                                <div class="col-md-6">
                                    <select name="assigned_to" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Brand Ambassador ---</option>
                                        @forelse ($brandAmbassadors as  $ba)
                                            <option value="{{ $ba->id }}">{{ strtoupper($ba->name) }}</option>
                                        @empty
                                            <option disabled> No Brand Ambassadors Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('assigned_to')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="quantity" class="col-md-4 col-form-label text-md-end">Quantity</label>

                                <div class="col-md-6">
                                    <input id="quantity" type="number"
                                        class="form-control @error('quantity') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="quantity" autocomplete="number"
                                        placeholder="200" required>
                                    @error('quantity')
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
        $("#submitQuery").click(function() {
            $("#invalidProductCode").hide();
            $("#isAssigned").hide();
            $("#isSuccess").hide();
        });

        $("#invalidProductCode").hide();
        $("#isAssigned").hide();
        $("#isSuccess").hide();
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#form-merchandise").validate({

            submitHandler: function() {

                var product_code = $("#product_code").val();
                var category_id = $("#category_id").val();
                var brand_id = $("#brand_id").val();
                var client_id = $("#client_id").val();
                var storage_id = $("#storage_id").val();
                var color = $("#color").val();
                var size = $("#size").val();
                // var quantity = $("#quantity").val();


                // processing ajax request
                $.ajax({
                    url: "{{ route('products.store') }}",
                    type: 'POST',
                    dataType: "json",
                    data: {
                        product_code: product_code,
                        category_id: category_id,
                        brand_id: brand_id,
                        client_id: client_id,
                        storage_id: storage_id,
                        color: color,
                        size: size,
                    },
                    success: function(data) {
                        if (data.status == 503) {
                            $('#product_code').addClass('is-invalid');
                            $('#product_code').removeClass('is-valid');
                            $("#invalidProductCode").hide();
                            $("#isAssigned").show();
                        }
                        if (data.status == 504) {
                            $('#product_code').addClass('is-invalid');
                            $('#product_code').removeClass('is-valid');
                            $("#invalidProductCode").show();
                            $("#isAssigned").hide();
                        }
                        if (data.status == 500) {
                            $('#product_code').addClass('is-invalid');
                            $('#product_code').removeClass('is-valid');
                            $("#invalidProductCode").show();
                            $("#isAssigned").hide();
                        }
                        if (data.status == 200) {
                            $('#product_code').removeClass('is-invalid');
                            $('#product_code').addClass('is-valid');
                            $("#invalidProductCode").hide();
                            $("#isAssigned").hide();
                            $("#isSuccess").show();
                            $("#product_code").val("");
                        }
                    }
                });
            }
        });
    </script>
@endsection
