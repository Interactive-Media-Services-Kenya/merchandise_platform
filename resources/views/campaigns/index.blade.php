@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
@can('admin_access')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('campaigns.create') }}">
            Add Campaign
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Campaigns
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-campaign" id="campaignTable">
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
                            Client
                        </th>
                        <th>
                            Brand
                        </th>
                        <th>
                            Start Date
                        </th>
                        <th>
                            End Date
                        </th>
                        <th>
                            Added By
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $key => $campaign)
                        <tr data-entry-id="{{ $campaign->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $campaign->id ?? '' }}
                            </td>
                            <td>
                                {{ $campaign->name ?? '' }}
                            </td>
                            <td>
                                {{ $campaign->client->name ?? '' }}
                            </td>
                            <td>
                                {{$campaign->brand->name?? 'No Brand'}}
                            </td>
                            <td>
                                {{ $campaign->from_date ?? '' }}
                            </td>
                            <td>
                                {{ $campaign->to_date ?? '' }}
                            </td>

                            <td>
                                {{ $campaign->user->name ?? '' }}
                            </td>

                            <td>
                                @can('admin_access')
                                    <a href="{{ route('campaigns.edit', [$campaign->id]) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
                                @endcan
                                @can('admin_access')
                                    <a href="{{ route('campaigns.destroyCampaign', [$campaign->id]) }}"
                                        class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No Campaigns Registered
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endcan
@can('tb_access')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('campaigns.create') }}">
                    Add campaign
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                campaigns
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable datatable-campaign" id="campaignTable">
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
                            @forelse($campaigns as $key => $campaign)
                                <tr data-entry-id="{{ $campaign->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $campaign->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $campaign->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $campaign->email ?? '' }}
                                    </td>
                                    <td>
                                        {{ $campaign->phone ?? '' }}
                                    </td>
                                    <td>
                                        {{ $campaign->address ?? '' }}
                                    </td>

                                    <td>
                                        @can('tb_access')
                                            <a href="{{ route('campaigns.edit', [$campaign->id]) }}"
                                                class="btn btn-primary btn-sm">Edit</a>
                                        @endcan
                                        @can('admin_access')
                                            <a href="{{ route('campaigns.destroycampaign', [$campaign->id]) }}"
                                                class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        No campaigns Registered
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
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"
        integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#campaignTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Campaign_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Campaign_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endsection
