@extends('layouts.backend')
@section('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
                            <h4 class="text-center">Issue {{strtoupper($product->category->title??'')}} ({{$product->product_code}}) to Customer</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('products.issue.product') }}">
                                @csrf
                                <div class="row mb-3">
                                    <label for="quantity" class="col-md-4 col-form-label text-md-end">Outlet Name</label>

                                    <div class="col-md-6">
                                        <select class="form-control outletsearch @error('outlet') is-invalid @enderror input" name="outlet" style="border: 1px solid; border-radius:10px;" required></select>
                                        @error('outlet')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="quantity" class="col-md-4 col-form-label text-md-end">Customer Name</label>
                                    <input type="hidden" name="product_id" value="{{$product->id}}">
                                    <input type="hidden" name="batch_id" value="{{$batch->id}}">
                                    <input type="hidden" name="latitude" id="latitude" value="0">
                                    <input type="hidden" name="longitude" id="longitude" value="0">
                                    <div class="col-md-6">
                                        <input
                                               class="form-control @error('quantity') is-invalid @enderror input"
                                               style="border: 1px solid; border-radius:10px;" name="customer_name" autocomplete="number"
                                               placeholder="FirstName  LastName">
                                        @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="quantity" class="col-md-4 col-form-label text-md-end">Customer Phone</label>

                                    <div class="col-md-6">
                                        <input id=""
                                               class="form-control @error('quantity') is-invalid @enderror input"
                                               style="border: 1px solid; border-radius:10px;" name="customer_phone"
                                               placeholder="254XXXXXXXXX">
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

        <script type="text/javascript">
            $('.outletsearch').select2({
                placeholder: 'Select Outlet',
                ajax: {
                    url: '/ajax-outlet-search',
                    dataType: 'json',
                    delay: 150,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        </script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script>
            $(document).ready(function() {
                setCurrentLocationCoordinates();
            });
            function setCurrentLocationCoordinates(){
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(getpos);
                } else {
                    alert('Use latest browser that supports geolocation');
                }
            }

            function getpos(position) {
                latx=position.coords.latitude;
                lonx=position.coords.longitude;
                // Show Lat and Lon
                const latitudeField = document.getElementById("latitude");
                const longitudeField = document.getElementById("longitude");
                latitudeField.value = latx;
                longitudeField.value = lonx;
            }
        </script>
    @endsection
