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
                                    Client</label>

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
                                    <input id="color" type="text" class="form-control @error('color') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="color" value="{{ old('color') }}" autocomplete="color" autofocus placeholder="Red,yelloe,green,blue">

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
                                    <input id="size" type="text" class="form-control @error('size') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="size" value="{{ old('size') }}" autocomplete="size" autofocus placeholder="S,M,L,XL">

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
                                    <input type="hidden" name="owner_id" value="{{Auth::id()}}">

                                    @error('batch_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="assigned_to" class="col-md-4 col-form-label text-md-end">Select Brand Ambassador</label>

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
