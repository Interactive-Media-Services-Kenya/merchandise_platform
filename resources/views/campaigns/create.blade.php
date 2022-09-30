@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <style>
        .input{
            border:1px solid;
            border-radius: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-8 offset-2">
            <div class="card">
                <div class="card-header"><h4 class="text-center">Add Campaign</h4></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('campaigns.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Campaign Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="name" required autocomplete="name" autofocus placeholder="Enter Campaign Title">

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
                                <select name="client_id" id="client_id" class="form-control dynamic"
                                    style="border: 1px solid; border-radius:10px;" data-dependent="brand_id">
                                    <option selected disabled>--- Select Client ---</option>
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
                        <div class="row mb-3">
                            <label for="brand_id" class="col-md-4 col-form-label text-md-end">Select Brand</label>

                            <div class="col-md-6">
                                <select name="brand_id" id="brand_id" class="form-control"
                                    style="border: 1px solid; border-radius:10px;">
                                    <option value="">Select Brand</option>
                                </select>

                                @error('brand_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">Address</label>

                            <div class="col-md-6">
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="address" value="{{ $client->address }}" required autocomplete="address" autofocus placeholder="P.O Box 123, CityName">

                                @error('address')
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
@endsection
@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script type='text/javascript'>

    $(document).ready(function(){

      // Client Change
      $('.dynamic').change(function(){
            if ($(this).val() != '') {
                var select = $(this).attr('id');
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dynamicdependent.fetch') }}",
                    method:"POST",
                    data:{select:select, value:value, _token:_token, dependent:dependent},
                    success:function(result)
                    {
                        $('#'+dependent).html(result);
                    }

                    })
                }
        });

        $('#client_id').change(function(){
            $('#brand_id').val('');
        });

      });

    </script>
@endsection
