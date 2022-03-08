@extends('layouts.backend')
@section('content')
@can('admin_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('clients.create') }}">
                Add Client
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        Clients
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-client">
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
                            Address
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $key => $client)
                        <tr data-entry-id="{{ $client->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $client->id ?? '' }}
                            </td>
                            <td>
                                {{ $client->name ?? '' }}
                            </td>
                            <td>
                                {{ $client->email ?? '' }}
                            </td>
                            <td>
                                {{ $client->phone ?? '' }}
                            </td>
                            <td>
                                {{$client->address ?? '' }}
                            </td>
                            @can('admin_access')
                            <td>
                                <a href="{{route('clients.edit', [$client->id])}}" class="btn btn-primary btn-sm">Edit</a>
                                <a href="{{route('clients.destroyClient',[$client->id])}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                            </td>
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No Clients Registered
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endcan

@endsection
