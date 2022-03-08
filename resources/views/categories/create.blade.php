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
                <div class="card-header"><h4 class="text-center">Add Merchandise Category</h4></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="title" class="col-md-4 col-form-label text-md-end">Merchandise Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror  input" style="border: 1px solid; border-radius:10px;" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus placeholder="T-Shirt, Mugs, Hoodies">

                                @error('title')
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
