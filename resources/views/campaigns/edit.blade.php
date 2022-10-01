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
                <div class="card-header"><h4 class="text-center">Edit Campaign: {{$campaign->name}}</h4></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('campaigns.update',[$campaign->id]) }}">
                        @csrf
                        @method('put')
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Campaign Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="name" value="{{$campaign->name}}" required autocomplete="name" autofocus placeholder="Enter Campaign Title">

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
                                    style="border: 1px solid; border-radius:10px;" data-dependent="brand_id" required>
                                    <option value="{{$campaign->client_id}}" selected>{{strtoupper($campaign->client->name)}}</option>
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
                                    <option value="">SELECT BRAND</option>
                                </select>

                                @error('brand_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Campaign Period (Start - End)</label>

                            <div class="row justify-content-center col-md-6">
                                <div class="col-md-6">
                                    <input id="from_date" type="date" value="{{$campaign->from_date}}"  class="form-control @error('from_date') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="from_date" required autocomplete="from_date" autofocus placeholder="Enter Campaign Title">

                                @error('from_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                                <div class="col-md-6">
                                    <input id="to_date" type="date" value="{{$campaign->to_date}}" class="form-control @error('to_date') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="to_date" required autocomplete="from_date" autofocus placeholder="Enter Campaign Title">

                                @error('to_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
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
