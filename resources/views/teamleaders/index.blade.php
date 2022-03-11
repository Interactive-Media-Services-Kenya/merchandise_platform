@extends('layouts.backend')
@section('content')
@can('tb_access')


<div class="card">
    <div class="card-header">
        Team Leaders
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-teamleader">
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
                            County
                        </th>
                        {{-- <th>
                            Actions
                        </th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($teamleaders as $key => $teamleader)
                        <tr data-entry-id="{{ $teamleader->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $teamleader->id ?? '' }}
                            </td>
                            <td>
                                {{ $teamleader->name ?? '' }}
                            </td>
                            <td>
                                {{ $teamleader->email ?? '' }}
                            </td>
                            <td>
                                {{ $teamleader->phone ?? '' }}
                            </td>
                            <td>
                                {{ $teamleader->county->name ?? '' }}
                            </td>
                            {{-- <td>
                                <a href="{{route('teamleaders.edit', [$teamleader->id])}}" class="btn btn-primary btn-sm">Edit</a>
                                @if(Auth::id() != $teamleader->id)<a href="{{route('teamleaders.destroyteamleader',[$teamleader->id])}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>@endif
                            </td> --}}

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endcan

@endsection
