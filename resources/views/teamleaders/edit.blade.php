@extends('layouts.backend')
@section('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- selec2 cdn --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                <div class="card-header"><h4 class="text-center">Edit Details for: {{$user->name}}</h4></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('teamleaders.update', [$user->id]) }}">
                        @csrf
                        @method('put')
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Teamleader Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus placeholder="East African Breweries Limmited, Coca Cola .. etc">

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="email" value="{{ $user->email }}" required autocomplete="email" autofocus placeholder="info@company.com">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="phone" value="{{ $user->phone }}" required autocomplete="phone" autofocus placeholder="2547XXXXXXXX">

                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="address" class="col-md-4 col-form-label text-md-end">Select Campaigns</label>

                            <div class="col-md-6">
                                <select name="campaign_id[]" id="js-example-basic-multiple" class="form-control select2-multiple" multiple="multiple"
                                        style="border: 1px solid; border-radius:10px;">
                                    @forelse ($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}">{{ strtoupper($campaign->name) }}</option>
                                    @empty
                                        <option disabled> No Campaign Added Yet</option>
                                    @endforelse
                                </select>

                                @error('campaign_id')
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Select2 Multiple
            $('.select2-multiple').select2({
                placeholder: "Select",
                allowClear: true
            });

        });

    </script>
@endsection
