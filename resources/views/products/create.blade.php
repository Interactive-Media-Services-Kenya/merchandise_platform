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
                                <select name="county_id" id="" class="form-control"
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
                                Team Leader</label>

                            <div class="col-md-6">
                                <select name="county_id" id="" class="form-control"
                                    style="border: 1px solid; border-radius:10px;">
                                    <option selected disabled>--- Select Team Leader ---</option>
                                    @forelse ($teamleaders as  $leader)
                                        <option value="{{ $leader->id }}">{{ strtoupper($leader->name) }}</option>
                                    @empty
                                        <option disabled> No Ambassadors Added Yet</option>
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
                            <label for="phone" class="col-md-4 col-form-label text-md-end">Quantity</label>

                            <div class="col-md-6">
                                <input id="quantity" type="number"
                                    class="form-control @error('quantity') is-invalid @enderror input"
                                    style="border: 1px solid; border-radius:10px;" name="quantity"
                                    required autocomplete="number" placeholder="200">

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
