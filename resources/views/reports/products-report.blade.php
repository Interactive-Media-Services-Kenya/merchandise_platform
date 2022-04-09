@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
@endsection
@section('content')
    @can('tb_access')
        <div class="row mt-4" height="200">
            <div class="col-lg-12 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-success shadow-primary border-radius-lg py-3 pe-1 text-center">
                            <br />
                            <h3>Select Date Range</h3>
                            <br />
                            <br />

                            <div class="col-md-5 mx-auto text-center">
                                <div class="row input-daterange">
                                    <div class="col-md-4">
                                        <input type="text" id="from_date" placeholder="From Date" class="form-control"
                                            readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="to_date" id="to_date" placeholder="To Date"
                                            class="form-control" readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" name="filter" id="filter"
                                            class="btn btn-sm btn-primary">Filter</button>
                                        <button type="button" name="refresh" id="refresh"
                                            class="btn btn-sm btn-info">Refresh</button>
                                    </div>
                                </div>
                            </div>

                            <br />
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Merchandise Issued</h6>
                        <div class="card">
                            <div class="card-header">
                                Merchandise
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 mx-auto">
                                    <div class="table-responsive">
                                        <table class=" table table-bordered table-striped table-hover datatable"
                                            id="productReportTable">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Merchandise
                                                    </th>
                                                    <th>
                                                        Brand Ambassador
                                                    </th>
                                                    <th>
                                                        Batch
                                                    </th>
                                                    <th>
                                                        Merchandise Type
                                                    </th>

                                                    <th>
                                                        Date Issued
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
        </div>
    @endcan
    @can('clients_access')
        <div class="row mt-4" height="200">
            <div class="col-lg-12 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-success shadow-primary border-radius-lg py-3 pe-1 text-center">
                            <br />
                            <h3>Select Date Range</h3>
                            <br />
                            <br />

                            <div class="col-md-5 mx-auto text-center">
                                <div class="row input-daterange">
                                    <div class="col-md-4">
                                        <input type="text" id="from_date" placeholder="From Date" class="form-control"
                                            readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="to_date" id="to_date" placeholder="To Date"
                                            class="form-control" readonly />
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" name="filter" id="filter"
                                            class="btn btn-sm btn-primary">Filter</button>
                                        <button type="button" name="refresh" id="refresh"
                                            class="btn btn-sm btn-info">Refresh</button>
                                    </div>
                                </div>
                            </div>

                            <br />
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Merchandise Issued</h6>
                        <div class="card">
                            <div class="card-header">
                                Merchandise
                            </div>
                            <div class="card-body">
                                <div class="col-md-12 mx-auto">
                                    <div class="table-responsive">
                                        <table class=" table table-bordered table-striped table-hover datatable"
                                            id="productReportTable">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Merchandise
                                                    </th>
                                                    <th>
                                                        Brand Ambassador
                                                    </th>
                                                    <th>
                                                        Batch
                                                    </th>
                                                    <th>
                                                        Merchandise Type
                                                    </th>

                                                    <th>
                                                        Date Issued
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
        </div>
    @endcan
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
    <script>
        const myDatePicker = MCDatepicker.create({
            el: '#from_date',
            dateFormat: 'MMM-DD-YYYY',
        })
        const myDatePicker2 = MCDatepicker.create({
            el: '#to_date',
            dateFormat: 'MMM-DD-YYYY',
        })
    </script>
    @can('tb_access')
        {{-- Team Leader --}}
        <script>
            $(document).ready(function() {
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });

                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#productReportTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('report.products') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [{
                                data: 'product_code',
                                name: 'product.product_code'
                            },
                            {
                                data: 'ba',
                                name: 'brandambassador.email'
                            },
                            {
                                data: 'batch',
                                name: 'batch.batch_code'
                            },
                            {
                                data: 'category',
                                name: 'category.title'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            }
                        ],
                        pageLength: 50,
                        dom: 'lBfrtip',
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
                            // {
                            //     extend: 'pdfHtml5',
                            //     title: 'merchandise_list',
                            //     exportOptions: {
                            //         columns: [0, 1, 2, 3, 4, 5]
                            //     }
                            // },
                            'colvis'
                        ]
                    });
                }

                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if (from_date != '' && to_date != '') {
                        $('#productReportTable').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        alert('Both Dates are required');
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#productReportTable').DataTable().destroy();
                    load_data();
                });

            });
        </script>
    @endcan


    @can('client_access')
        {{-- Client Leader --}}
        <script>
            $(document).ready(function() {
                $('.input-daterange').datepicker({
                    todayBtn: 'linked',
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });

                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#productReportTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('report.products.client') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [{
                                data: 'product_code',
                                name: 'product.product_code'
                            },
                            {
                                data: 'ba',
                                name: 'brandambassador.email'
                            },
                            {
                                data: 'batch',
                                name: 'batch.batch_code'
                            },
                            {
                                data: 'category',
                                name: 'category.title'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            }
                        ],
                        pageLength: 50,
                        dom: 'lBfrtip',
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
                            // {
                            //     extend: 'pdfHtml5',
                            //     title: 'merchandise_list',
                            //     exportOptions: {
                            //         columns: [0, 1, 2, 3, 4, 5]
                            //     }
                            // },
                            'colvis'
                        ]
                    });
                }

                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if (from_date != '' && to_date != '') {
                        $('#productReportTable').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        alert('Both Dates are required');
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    $('#productReportTable').DataTable().destroy();
                    load_data();
                });

            });
        </script>
    @endcan
@endsection
