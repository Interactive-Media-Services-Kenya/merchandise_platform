@extends('layouts.backend')
@section('content')
@can('team_leader_access')


<div class="card">
    <div class="card-header">
        Brand Ambassadors
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-brandambassador">
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
                    @foreach($brandambassadors as $key => $brandambassador)
                        <tr data-entry-id="{{ $brandambassador->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $brandambassador->id ?? '' }}
                            </td>
                            <td>
                                {{ $brandambassador->name ?? '' }}
                            </td>
                            <td>
                                {{ $brandambassador->email ?? '' }}
                            </td>
                            <td>
                                {{ $brandambassador->phone ?? '' }}
                            </td>
                            <td>
                                {{ $brandambassador->county->name ?? '' }}
                            </td>
                            {{-- <td>
                                <a href="{{route('brandambassadors.edit', [$brandambassador->id])}}" class="btn btn-primary btn-sm">Edit</a>
                                @if(Auth::id() != $brandambassador->id)<a href="{{route('brandambassadors.destroybrandambassador',[$brandambassador->id])}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>@endif
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
