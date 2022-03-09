@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
    @can('tb_access')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('products.create') }}">
                    Add Merchandise
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Merchandise
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Merchandise Type
                                </th>
                                <th>
                                    Client
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    Team Leader
                                </th>
                                <th>
                                    Batch Code
                                </th>
                                <th>
                                    Date Added
                                </th>
                                @can('admin_access')
                                <th>
                                    Actions
                                </th>
                                @endcan

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $key => $product)
                                <tr data-entry-id="{{ $product->id }}">
                                    <td>

                                    </td>
                                    <td>
                                        {{ $product->id ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->category->title ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->client->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->product_code ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->assign->email ?? '' }}
                                    </td>
                                    <td>
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
                                    </td>
                                    <td>
                                        {{ $product->created_at ?? '' }}
                                    </td>
                                    @can('admin_access')
                                    <td>
                                        <a href="{{ route('products.edit', [$product->id]) }}"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="{{ route('products.destroyProduct', [$product->id]) }}"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                    </td>
                                    @endcan

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan
    @can('team_leader_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('products.create') }}">
                Add Merchandise
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Merchandise
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>
                                ID
                            </th>
                            <th>
                                Merchandise Type
                            </th>
                            <th>
                                Client
                            </th>
                            <th>
                                Serial Number
                            </th>
                            <th>
                                Team Leader
                            </th>
                            <th>
                                Batch Code
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr data-entry-id="{{ $product->id }}">
                                <td>

                                </td>
                                <td>
                                    {{ $product->id ?? '' }}
                                </td>
                                <td>
                                    {{ $product->category->title ?? '' }}
                                </td>
                                <td>
                                    {{ $product->client->name ?? '' }}
                                </td>
                                <td>
                                    {{ $product->product_code ?? '' }}
                                </td>
                                <td>
                                    {{ $product->assign->email ?? '' }}
                                </td>
                                <td>
                                    {{ $product->batch->batch_code ?? 'Single Product' }}
                                </td>
                                <td>
                                    <a href="{{ route('products.edit', [$product->id]) }}"
                                        class="btn btn-primary btn-sm">Edit</a>
                                    <a href="{{ route('products.destroyProduct', [$product->id]) }}"
                                        class="btn btn-danger btn-sm" onclick="return confirm('Are you Sure?')">Delete</a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endcan

@endsection
@section('scripts')
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
            $('#ProductTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Merchandise_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'merchandise_list',
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
