@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
    @can('tb_access')
        <div class="row mb-4 mt-4">
            <div class="col-lg-12 mx-auto mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>{{ $agency->name}} : Agency</h6>
                                {{-- <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">{{ count($batchesbas) }}</span> Assigned and
                            Confirmed
                        </p>
                        <p class="text-sm mb-0">
                            <i class="fa fa-times text-danger" aria-hidden="true"></i>
                            <span class="font-weight-bold ms-1">{{ count($batchesbas) }}</span> Assigned and
                            Rejected
                        </p> --}}
                            </div>
                            <div class="col-lg-6 col-5 my-auto text-end">
                                {{-- <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-secondary"></i>
                            </a>
                            <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                </li>
                                <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                        here</a></li>
                            </ul>
                        </div> --}}
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-2 pb-2">
                        <div class="table-responsive mt-3">
                            <table class="table align-items-center mb-3 datatable" id="batchTable">
                                <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Batch Code</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Merchandise Count</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Date Added</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($batches as $batch)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $batch->batch_code }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="avatar-group mt-2">
                                                <h6>{{ count($batch->products) }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $batch->created_at }}</h6>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2">No Assigned Batches</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('client_access')
        <div class="row mb-4 mt-4">
            <div class="col-lg-12 mx-auto mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Agency: {{ strtoupper($agency->name)}}</h6>
                                 <p class="text-sm mb-0">
                            <i class="fa fa-check text-info" aria-hidden="true"></i>
                             Total Merchandise : <span class="font-weight-bold ms-1">{{ $products->count() }}</span>
                        </p>
                        <p class="text-sm mb-0">
                            <i class="fa fa-check-double text-success" aria-hidden="true"></i>
                             Total Issued Out: <span class="font-weight-bold ms-1">{{ $issuedProductsClient->count() }}</span>
                        </p>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-times text-warning" aria-hidden="true"></i>
                                    Total Balance: <span class="font-weight-bold ms-1">{{ $products->count()-$issuedProductsClient->count() }}</span>
                                </p>
                            </div>

                        </div>
                    </div>
                    <div class="card-body px-2 pb-2">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
                                    <thead>
                                    <tr>
                                        <th>
                                            ID
                                        </th>
                                        <th>
                                            Merchandise Type
                                        </th>
                                        <th>
                                            Serial Number
                                        </th>
{{--                                        <th>--}}
{{--                                            BarCode--}}
{{--                                        </th>--}}
                                        <th>
                                            Date Added
                                        </th>
                                        <th>
                                            Date Issued
                                        </th>
                                        <th>
                                            Actions
                                        </th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
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
                processing: true,
                method: 'GET',
                serverSide: true,
                ajax: "{{ route('products.index.agency',[$agency->id]) }}",
                {{--ajax: "{{ route('products.index') }}",--}}
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'category',
                        name: 'category.title'
                    },
                    {
                        data: 'product_code',
                        name: 'product_code'
                    },
                    // {
                    //     data: 'bar_code',
                    //     name: 'bar_code',
                    //     searchable: false
                    // },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'issued_at',
                        name: 'issued_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                dom: 'lBfrtip',
                pageLength: 100,
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Merchandise_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'merchandise_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endsection

