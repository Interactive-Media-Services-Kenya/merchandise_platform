@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <style>
        .input {
            border: 1px solid;
            border-radius: 10px;
        }

    </style>
@endsection
@section('content')
    @can('admin_access')
        <div class="row">
            <div class="col-sm-8 offset-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Add User</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror  input"
                                        style="border: 1px solid; border-radius:10px;" name="name" value="{{ old('name') }}"
                                        required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="email" value="{{ old('email') }}"
                                        required autocomplete="email" placeholder="somebody@example.com">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">Phone Number</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="phone" value="{{ old('phone') }}"
                                        required autocomplete="phone" placeholder="2547XXXXXXXX">

                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select County</label>

                                <div class="col-md-6">
                                    <select name="county_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select County ---</option>
                                        @forelse ($counties as $id => $county)
                                            <option value="{{ $id }}">{{ strtoupper($county) }}</option>
                                        @empty
                                            <option disabled> No County Added Yet</option>
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
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select Role</label>

                                <div class="col-md-6">
                                    <select name="role_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select Role ---</option>
                                        @forelse ($roles as $id => $role)
                                            <option value="{{ $id }}">{{ strtoupper($role) }}</option>
                                        @empty
                                            <option disabled> No Roles Added Yet</option>
                                        @endforelse
                                    </select>

                                    @error('role_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="password" required
                                        autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control input"
                                        style="border: 1px solid; border-radius:10px;" name="password_confirmation" required
                                        autocomplete="new-password">
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
                        <h4 class="text-center">Add A Sales Representative</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror  input"
                                        style="border: 1px solid; border-radius:10px;" name="name" value="{{ old('name') }}"
                                        required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="email"
                                        value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="somebody@example.com">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-form-label text-md-end">Phone Number</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="phone"
                                        value="{{ old('phone') }}" required autocomplete="phone" placeholder="2547XXXXXXXX">
                                    <input type="hidden" name="role_id" value="3">
                                    <input type="hidden" name="client_id" value="{{Auth::user()->client_id}}">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="county_id" class="col-md-4 col-form-label text-md-end">Select County</label>

                                <div class="col-md-6">
                                    <select name="county_id" id="" class="form-control"
                                        style="border: 1px solid; border-radius:10px;">
                                        <option selected disabled>--- Select County ---</option>
                                        @forelse ($counties as $id => $county)
                                            <option value="{{ $id }}">{{ strtoupper($county) }}</option>
                                        @empty
                                            <option disabled> No County Added Yet</option>
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
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror input"
                                        style="border: 1px solid; border-radius:10px;" name="password" required
                                        autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control input"
                                        style="border: 1px solid; border-radius:10px;" name="password_confirmation" required
                                        autocomplete="new-password">
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
