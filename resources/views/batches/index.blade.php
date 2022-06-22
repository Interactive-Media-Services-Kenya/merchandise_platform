@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
    @can('team_leader_access')
        <div class="row mb-4 mt-4">
            <div class="col-lg-12 mx-auto mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Batches</h6>
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
                            <table class="table align-items-center mb-3 datatable" id="ProductTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Batch</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Merchandise Type</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Merchandise</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Merchandise Assigned</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Date Added</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status (Confirm)</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Date Confirmed</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($batchesTls as $batch)
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
                                                    @php
                                                        $categoryProduct = \App\Models\Product::wherebatch_tl_id($batch->id)->first()
                                                    @endphp
                                                    <h6>{{ $categoryProduct->category->title?? ''}}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ count(
                                                        \DB::table('products')->where('assigned_to', Auth::id())->where('batch_id', $batch->id)->get(),
                                                    ) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ count(\DB::table('productbas')->where('batch_id',$batch->id)->get()) ?? '' }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ $batch->created_at ?? '' }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ $batch->accept_status == 1 ? 'Confirmed' : 'Not Confirmed' }}</span>
                                            </td>

                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ $batch->updated_at == $batch->created_at ? 'Not Confirmed' : $batch->updated_at }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm"><a
                                                    href="{{ route('batch.show', [$batch->id]) }}"
                                                    class="btn btn-sm btn-info">View</a>
                                                {{-- <a href="#"
                                        class="btn btn-sm btn-success">Confirm</a> &nbsp;
                                        <a href="#"
                                        class="btn btn-sm btn-danger">Reject</a> --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">No Assigned Batches</td>
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
    @can('brand_ambassador_access')
        <div class="row mb-4 mt-4">
            <div class="col-lg-12 mx-auto mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Batches</h6>
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
                                            Batch</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Merchandise Type</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Total Merchandise</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Merchandise Issued Out</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($batchesbas as $batch)
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
                                                    <h6>{{ $batch->product->category->title }}</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ count(
                                                        \DB::table('products')->where('products.batch_id', $batch->id)->join('productbas', 'productbas.product_id', 'products.id')->where('productbas.assigned_to', Auth::id())->get(),
                                                    ) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ count(
                                                        \DB::table('issue_products')->where('batch_id', $batch->id)->where('ba_id', Auth::id())->get(),
                                                    ) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm"><a
                                                    href="{{ route('batch.show', [$batch->batch_id]) }}"
                                                    class="btn btn-sm btn-info">View</a>
                                                {{-- <a href="#"
                                        class="btn btn-sm btn-success">Confirm</a> &nbsp;
                                        <a href="#"
                                        class="btn btn-sm btn-danger">Reject</a> --}}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">No Assigned Batches</td>
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
