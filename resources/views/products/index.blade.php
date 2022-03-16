@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
    {{-- Admin and TB Access --}}
    @can('admin_access')
        @can('tb_access')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route('products.create') }}">
                        Add Merchandise
                    </a>
                </div>
            </div>
        @endcan

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
                                @can('tb_access')
                                    <th>
                                        Team Leader
                                    </th>
                                @endcan
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
                                    @can('tb_access')
                                        <td>
                                            {{ $product->assign->email ?? '' }}
                                        </td>
                                    @endcan
                                    <td>
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
                                    </td>
                                    <td>
                                        {{ $product->created_at ?? '' }}
                                    </td>
                                    @can('admin_access')
                                        <td>
                                            {{-- <a href="{{ route('products.edit', [$product->id]) }}"
                                                class="btn btn-primary btn-sm">Edit</a> --}}
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

    {{-- Admin and TB Access --}}
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
                                @can('tb_access')
                                    <th>
                                        Team Leader
                                    </th>
                                @endcan
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
                                    @can('tb_access')
                                        <td>
                                            {{ $product->assign->email ?? '' }}
                                        </td>
                                    @endcan
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

    {{-- Team Leader Access --}}
    @can('team_leader_access')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('products.create') }}">
                    Assign Merchandise
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
                                    Asigned To
                                </th>
                                <th>
                                    Batch Code
                                </th>
                                @can('admin_access')
                                    <th>
                                        Actions
                                    </th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productsTls as $key => $product)
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
                                    {{-- <td>
                                        {{ \DB::table('users')->where('id',$product->productBa->assigned_to)->value('email') ?? '' }}
                                    </td> --}}
                                    <td>
                                        {{ \DB::table('productbas')->where('product_id', $product->id)->value('assigned_to')
                                            ? \DB::table('users')->where(
                                                    'id',
                                                    \DB::table('productbas')->where('product_id', $product->id)->value('assigned_to'),
                                                )->value('email')
                                            : 'Not Assigned' }}
                                    </td>
                                    <td>
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
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

    {{-- Brand Ambassador --}}
    @can('brand_ambassador_access')
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Merchandise</h6>
                    </div>

                    <div class="col-md-6 text-center"><a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                            data-target="#staticBackdropIssueBatch">Issue Out Batch</a>
                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdropIssueBatch" data-backdrop="static" data-keyboard="false"
                            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Batch Issue Merchandise
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('products.issue.batch') }}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group mt-4">
                                                <label for="reason">
                                                    <h6>Select Batch</h6>
                                                </label>
                                                <select name="batch_id" class="form-control" style="border:solid 1px;"
                                                    required>
                                                    <option selected disabled value="">Select Batch</option>
                                                    @foreach ($batchesBa as $batch)
                                                        <option value="{{ $batch->id }}">
                                                            {{ strtoupper($batch->batch_code) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group mt-4">
                                                <label for="quantity">
                                                    Quantity
                                                </label>
                                                <input type="number" name="quantity" class="form-control"
                                                    style="border:solid 1px;" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
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
                                    Batch Code
                                </th>
                                <th>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productsBas as $key => $product)
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
                                        {{ $product->batch->batch_code ?? 'Single Product' }}
                                    </td>
                                    <td><a href="/products/issue/product/{{ $product->id }}/{{ $product->batch->id }}"
                                            class="btn btn-sm btn-warning">Issue Out</a></td>

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
