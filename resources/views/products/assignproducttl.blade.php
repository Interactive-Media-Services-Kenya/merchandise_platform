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
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('products.assign.create') }}">
                Assign Merchandise To Agency
            </a> <a class="btn btn-primary" href="{{ route('products.assign.brandambbassador') }}">
                Assign Merchandise To BrandAmbassadors
            </a>
        </div>
    </div>
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Assign Merchandise To TeamLeader</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.storeTL') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="batch_id" class="col-md-4 col-form-label text-md-end">Select
                                    Merchandise Type</label>

                                <div class="col-md-6">
                                    <select name="category_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Merchandise Type ---</option>
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}">{{ strtoupper($category->title) }}</option>
                                        @empty
                                            <option disabled> No Categories To Select Yet</option>
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
                                    <select name="client_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Client ---</option>
                                        @forelse ($clients as  $client)
                                            <option value="{{ $client->id }}">{{ strtoupper($client->name) }}</option>
                                        @empty
                                            <option disabled> No Clients Added Yet</option>
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
                                        <option selected disabled>--- Select Brand ---</option>
                                        @forelse ($brands as  $brand)
                                            <option value="{{ $brand->id }}">{{ strtoupper($brand->name)}} --- {{ $brand->client->name}}</option>
                                        @empty
                                            <option disabled> No Brands Added Yet</option>
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
                                <label for="size" class="col-md-4 col-form-label text-md-end">Select
                                    Size</label>

                                <div class="col-md-6">
                                    <select name="size" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Size ---</option>
                                        @forelse ($sizes as  $size)
                                            <option value="{{ $size->id }}">{{ strtoupper($size->name) }}</option>
                                        @empty
                                            <option disabled> No Ambassadors Added Yet</option>
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
                                <label for="color" class="col-md-4 col-form-label text-md-end">Select
                                    Color</label>

                                <div class="col-md-6">
                                    <select name="color" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Color ---</option>
                                        @forelse ($colors as  $color)
                                            <option value="{{ $color->id }}">{{ strtoupper($color->name) }}</option>
                                        @empty
                                            <option disabled> No Ambassadors Added Yet</option>
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
                                <label for="team_leader_id" class="col-md-4 col-form-label text-md-end">Select
                                    Team Leader User</label>

                                <div class="col-md-6">
                                    <select name="team_leader_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Team Leader ---</option>
                                        @forelse ($teamleaders as $leader)
                                            <option value="{{ $leader->id }}">{{ strtoupper($leader->email) }}</option>
                                        @empty
                                            <option disabled> No Team Leader</option>
                                        @endforelse
                                    </select>
                                    @error('team_leader_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3" id="batch">
                                <label for="quantity" class="col-md-4 col-form-label text-md-end">Quantity</label>

                                <div class="col-md-6">
                                    <input id="quantity" type="number" min="1"
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
                            {{-- <div class="row mb-3">
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

                    </div> --}}

                            {{-- <div class="row mb-3" id="batch">
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
                    </div> --}}

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
    @can('tb_access')
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Assign Merchandise from Batch</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.storeTL') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="batch_id" class="col-md-4 col-form-label text-md-end">Select
                                    Batch</label>

                                <div class="col-md-6">
                                    <select name="batch_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Batch Code ---</option>
                                        @forelse ($batchesAll as $batch)
                                            <option value="{{ $batch->id }}">{{ strtoupper($batch->batch_code) }}
                                            </option>
                                        @empty
                                            <option disabled> No Batches To Select Yet</option>
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
                                    Team Leader</label>

                                <div class="col-md-6">
                                    <select name="assigned_to" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Team Leader ---</option>
                                        @forelse ($teamleaders as  $leader)
                                            <option value="{{ $leader->id }}">{{ strtoupper($leader->name) }}</option>
                                        @empty
                                            <option disabled> No Ambassadors Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('assigned_to')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="row mb-3">
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

                            </div> --}}

                            {{-- <div class="row mb-3" id="batch">
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
                            </div> --}}

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
                        <h4 class="text-center">Assign Merchandise from Batch</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.storeTL') }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="batch_id" class="col-md-4 col-form-label text-md-end">Select
                                    Batch</label>

                                <div class="col-md-6">
                                    <select name="batch_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Batch Code ---</option>
                                        @forelse ($batchesClient as $batch)
                                            <option value="{{ $batch->id }}">{{ strtoupper($batch->batch_code) }}
                                            </option>
                                        @empty
                                            <option disabled> No Batches To Select Yet</option>
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
                                <label for="assigned_to" class="col-md-4 col-form-label text-md-end">Select
                                    Sales Representative</label>

                                <div class="col-md-6">
                                    <select name="assigned_to" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Sales Representative ---</option>
                                        @forelse ($salesreps as  $leader)
                                            <option value="{{ $leader->id }}">{{ strtoupper($leader->name) }}</option>
                                        @empty
                                            <option disabled> No Sales Representative Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('assigned_to')
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
    {{-- <script>
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
    </script> --}}
@endsection
