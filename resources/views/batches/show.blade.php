@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
    @can('tb_access')
        <div class="card">
            <div class="card-header">
                <h4>Merchandise In Batch</h4>
            </div>

            <div class="card-body">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>BATCH-CODE
                                : {{ strtoupper($batch->batch_code) }}</h5>
                            <a href="{{ route('batch.confirm', [$batch->id]) }}" onclick="return confirm('Are you Sure?')"
                                class="btn btn-sm btn-success">Confirm
                                Batch</a>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#staticBackdropRejectAll{{ $batch->id }}">Reject Batch</a>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdropRejectAll{{ $batch->id }}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div lass="modal-dialog">
                                    <cdiv class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">{{ $batch->batch_code }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('reject.batch', [$batch->id]) }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group mt-4">
                                                    <label for="reason">
                                                        <h6>Select Reason</h6>
                                                    </label>
                                                    <select name="reason_id" class="form-control" style="border:solid 1px;">
                                                        <option selected disabled>Select Reason</option>
                                                        @foreach ($reasons as $reason)
                                                            <option value="{{ $reason->id }}">
                                                                {{ strtoupper($reason->title) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mt-4">
                                                    <label for="description">
                                                        <h6>Reason Description</h6>
                                                    </label>
                                                    <textarea class="form-control" name="description" id="" cols="20" rows="5"
                                                        style="border:solid 1px;"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                                    <thead>
                                        <tr>

                                            {{-- <th>
                                                    ID
                                                </th> --}}
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
                                            <th>Status (Confirm)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productsTl as $key => $product)
                                            <tr data-entry-id="{{ $product->id }}">
                                                {{-- <td>
                                                        {{ $product->id ?? '' }}
                                                    </td> --}}
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
                                                    {{ $product->batch_code ?? 'Single Product' }}
                                                </td>
                                                <td> {{ $product->batch->accept_status == 1 ? 'Confirmed' : 'Not Confirmed' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endcan
    @can('team_leader_access')
        <div class="card">
            <div class="card-header">
                Batch
            </div>

            <div class="card-body">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>BATCH-CODE
                                : {{ strtoupper($batch->batch_code) }}</h5>
                            <a href="{{ route('products.confirm.batch', [$batch->id]) }}"
                                class="btn btn-sm btn-success">Confirm
                                Batch</a>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#staticBackdropRejectAll{{ $batch->id }}">Reject Batch</a>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdropRejectAll{{ $batch->id }}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">{{ $batch->batch_code }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('products.reject.batch', [$batch->id]) }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group mt-4">
                                                    <label for="reason">
                                                        <h6>Select Reason</h6>
                                                    </label>
                                                    <select name="reason_id" class="form-control" style="border:solid 1px;">
                                                        <option selected disabled>Select Reason</option>
                                                        @foreach ($reasons as $reason)
                                                            <option value="{{ $reason->id }}">
                                                                {{ strtoupper($reason->title) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mt-4">
                                                    <label for="description">
                                                        <h6>Reason Description</h6>
                                                    </label>
                                                    <textarea class="form-control" name="description" id="" cols="20" rows="5"
                                                        style="border:solid 1px;"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class=" table table-bordered table-striped table-hover datatable"
                                        id="ProductTable">
                                        <thead>
                                            <tr>

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
                                                {{-- <th>Status (Confirm)</th> --}}
                                                <th>
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $key => $product)
                                                <tr data-entry-id="{{ $product->id }}">
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
                                                    <td> {{ $product->accept_status == 1 ? 'Confirmed' : 'Not Confirmed' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('product.confirm', [$product->id]) }}"
                                                            class="btn btn-success btn-sm"
                                                            onclick="return confirm('Are you Sure?')">Confirm</a>
                                                        <!-- Button trigger modal -->
                                                        <a class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#staticBackdrop{{ $product->id }}">
                                                            Reject
                                                        </a>


                                                    </td>
                                                </tr>
                                                <!-- Modal -->
                                                <div class="modal fade" id="staticBackdrop{{ $product->id }}"
                                                    data-backdrop="static" data-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabel">
                                                                    {{ $product->product_code }}
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('product.reject', [$product->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-group mt-4">
                                                                        <label for="reason">
                                                                            <h6>Select Reason</h6>
                                                                        </label>
                                                                        <select name="reason_id" class="form-control"
                                                                            style="border:solid 1px;">
                                                                            <option selected disabled>Select Reason</option>
                                                                            @foreach ($reasons as $reason)
                                                                                <option value="{{ $reason->id }}">
                                                                                    {{ strtoupper($reason->title) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group mt-4">
                                                                        <label for="description">
                                                                            <h6>Reason Description</h6>
                                                                        </label>
                                                                        <textarea class="form-control" name="description" id="" cols="20" rows="5"
                                                                            style="border:solid 1px;"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('brand_ambassador_access')
        <div class="card">
            <div class="card-header">
                Batch
            </div>

            <div class="card-body">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5>BATCH-CODE
                                : {{ strtoupper($batch->batch_code) }}</h5>
                            <a href="{{ route('products.confirm.batch', [$batch->id]) }}"
                                class="btn btn-sm btn-success">Confirm
                                Batch</a>
                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                data-target="#staticBackdropRejectAll{{ $batch->id }}">Reject Batch</a>
                            <!-- Modal -->
                            <div class="modal fade" id="staticBackdropRejectAll{{ $batch->id }}" data-backdrop="static"
                                data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">{{ $batch->batch_code }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{ route('products.reject.batch', [$batch->id]) }}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group mt-4">
                                                    <label for="reason">
                                                        <h6>Select Reason</h6>
                                                    </label>
                                                    <select name="reason_id" class="form-control" style="border:solid 1px;">
                                                        <option selected disabled>Select Reason</option>
                                                        @foreach ($reasons as $reason)
                                                            <option value="{{ $reason->id }}">
                                                                {{ strtoupper($reason->title) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mt-4">
                                                    <label for="description">
                                                        <h6>Reason Description</h6>
                                                    </label>
                                                    <textarea class="form-control" name="description" id="" cols="20" rows="5"
                                                        style="border:solid 1px;"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class=" table table-bordered table-striped table-hover datatable"
                                        id="ProductTable">
                                        <thead>
                                            <tr>

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
                                                <th>Status (Confirm)</th>
                                                <th>
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $key => $product)
                                                <tr data-entry-id="{{ $product->id }}">
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
                                                    <td> {{ $product->accept_status == 1 ? 'Confirmed' : 'Not Confirmed' }}
                                                    </td>
                                                    <td>
                                                       @if($product->accept_status == 0) <a href="{{ route('product.confirm', [$product->id]) }}"
                                                            class="btn btn-success btn-sm"
                                                            onclick="return confirm('Are you Sure?')">Confirm</a>
                                                        @endif
                                                        <!-- Button trigger modal -->

                                                        <a class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#staticBackdrop{{ $product->id }}">
                                                            Reject
                                                        </a>


                                                    </td>
                                                </tr>
                                                <!-- Modal -->
                                                <div class="modal fade" id="staticBackdrop{{ $product->id }}"
                                                    data-backdrop="static" data-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabel">
                                                                    {{ $product->product_code }}
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ route('product.reject', [$product->id]) }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <div class="form-group mt-4">
                                                                        <label for="reason">
                                                                            <h6>Select Reason</h6>
                                                                        </label>
                                                                        <select name="reason_id" class="form-control"
                                                                            style="border:solid 1px;">
                                                                            <option selected disabled>Select Reason</option>
                                                                            @foreach ($reasons as $reason)
                                                                                <option value="{{ $reason->id }}">
                                                                                    {{ strtoupper($reason->title) }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group mt-4">
                                                                        <label for="description">
                                                                            <h6>Reason Description</h6>
                                                                        </label>
                                                                        <textarea class="form-control" name="description" id="" cols="20" rows="5"
                                                                            style="border:solid 1px;"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
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
