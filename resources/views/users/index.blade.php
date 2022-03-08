@extends('layouts.backend')
@section('content')
@can('user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.users.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                           ID
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $user->id ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td>
                                {{ $user->phone ?? '' }}
                            </td>
                            <td>
                                {{ strtoupper(\DB::table('roles')->where('id',$user->id)->value('title'))}}
                            </td>
                            <td>
                                <a href="#" class="btn btn-primary btn-sm">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm">Delete</a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
