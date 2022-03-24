@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> --}}
@endsection
@section('content')
    @can('admin_access')
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise</p>
                            <h4 class="mb-0">{{ count($products) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask
                        week</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Registered Brand Ambassadors</p>
                            <h4 class="mb-0">{{ count($bas) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask
                        month</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Clients</p>
                            <h4 class="mb-0">{{ count($clients) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday
                    </p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise Batches</p>
                            <h4 class="mb-0">{{ count($batches) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than
                        yesterday</p> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4" height="200">
            <div class="col-lg-6 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-default shadow-primary border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="myChart-bar" class="chart-canvas" height="450"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Merchandise Issued</h6>
                        <p class="text-sm ">Total Count form all Brand Ambassadors</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm"> per month </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-4 mb-3">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                            <div class="chart align-items-center" height="500">
                                <canvas id="myChart-pie" class="chart-canvas" height="450" style="margin: 0 auto;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Completed Tasks</h6>
                        <p class="text-sm ">Updated Issued Merchandise Per Type Summary</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm">latest update</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Recent Registered Users</h6>
                                <p class="text-sm mb-0">
                                    <i class="fa fa-check text-info" aria-hidden="true"></i>
                                    <span class="font-weight-bold ms-1">Registered</span> this month
                                </p>
                            </div>
                            {{-- <div class="col-lg-6 col-5 my-auto text-end">
                                <div class="dropdown float-lg-end pe-4">
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
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="col-md-11 mx-auto">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0" id="userTable">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Email</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Phone</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Role</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Date Registered</th>
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
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>Recent Activities</h6>
                        {{-- <p class="text-sm">
                            <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                            <span class="font-weight-bold">24%</span> this month
                        </p> --}}
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            @forelse ($activityAdmin as $activity)
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient">notifications</i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $activity->description }}
                                        </h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient">notifications</i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">No Activity Yet</h6>
                                        {{-- <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{$activity->created_at->diffForhums}}</p> --}}
                                    </div>
                                </div>
                            @endforelse
                            <a href="{{ route('activities') }}" class="btn btn-primary">All Activities</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('tb_access')
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise</p>
                            <h4 class="mb-0">{{ count($products) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask
                        week</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Registered Team Leaders</p>
                            <h4 class="mb-0">{{ count($tls) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask
                        month</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Clients</p>
                            <h4 class="mb-0">{{ count($clients) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday
                    </p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise Batches</p>
                            <h4 class="mb-0">{{ count($batches) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than
                        yesterday</p> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4" height="200">
            <div class="col-lg-6 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-default shadow-primary border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="myChart-bar" class="chart-canvas" height="450"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Merchandise Issued</h6>
                        <p class="text-sm ">Total Count form all Brand Ambassadors</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm"> per month </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-4 mb-3">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                            <div class="chart align-items-center" height="500">
                                <canvas id="myChart-pie" class="chart-canvas" height="450" style="margin: 0 auto;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Completed Tasks</h6>
                        <p class="text-sm ">Updated Issued Merchandise Per Type Summary</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm">latest update</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row mb-4">
            <div class="col-lg-8 mt-4 mb-3">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-light shadow-dark border-radius-lg py-3 pe-1">
                            <div class="chart align-items-center" height="500">
                                <canvas id="myChart-lines" class="chart-canvas" height="450"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Completed Tasks</h6>
                        <p class="text-sm ">Updated Issued Merchandise Per Type Summary</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm">latest update</p>
                        </div>
                    </div>
                </div>
            </div> --}}
        <div class="row mb-4">
            <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Latest Batches Confirmed</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="col-md-10 mx-auto table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Confirmed At</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Batch Code</th>

                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Team Leader</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($batchesConfirmed as $batch)
                                        <tr>
                                            <td>
                                                {{ $batch->updated_at }}
                                            </td>
                                            <td>
                                                {{ $batch->batch_code }}
                                            </td>
                                            <td>
                                                {{ $batch->teamleader->name }} ({{ $batch->teamleader->email }})
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('batch.show', [$batch->id]) }}"
                                                    class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="4">No Batches Assigned Yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>My Recent Activities</h6>
                        {{-- <p class="text-sm">
                            <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                            <span class="font-weight-bold">24%</span> this month
                        </p> --}}
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            @forelse ($activities as $activity)
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient">notifications</i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $activity->description }}
                                        </h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient">notifications</i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">No Activity Yet</h6>
                                        {{-- <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{$activity->created_at->diffForhums}}</p> --}}
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('team_leader_access')
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <a href="{{ route('brandambassadors') }}"></a>
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise</p>
                            <h4 class="mb-0">{{ count($productsTls) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask
                        week</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Brand Ambassadors</p>
                            <h4 class="mb-0">{{ count($brandAmbassadors) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask
                        month</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Clients</p>
                            <h4 class="mb-0">{{ count($clients) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday
                    </p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Merchandise Batches</p>
                            <h4 class="mb-0">{{ count($batchesTl) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than
                        yesterday</p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row mt-4">
            <div class="col-lg-4 col-md-6 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Website Views</h6>
                        <p class="text-sm ">Last Campaign Performance</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm"> campaign sent 2 days ago </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-4 mb-4">
                <div class="card z-index-2  ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 "> Daily Sales </h6>
                        <p class="text-sm "> (<span class="font-weight-bolder">+15%</span>) increase in today sales.
                        </p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm"> updated 4 min ago </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mb-3">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Completed Tasks</h6>
                        <p class="text-sm ">Last Campaign Performance</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm">just updated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row mb-4 mt-4">
            <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg-6 col-7">
                                <h6>Batches</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pb-2">
                        <div class="col-md-10 mx-auto table-responsive">
                            <table class="table align-items-center mb-0" id="batchTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Batch Code</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Merchandise Type</th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($batchesTl as $batch)
                                        <tr>
                                            <td>
                                                {{ $batch->batch->batch_code }}
                                            </td>
                                            <td>
                                                {{ $batch->category->title }}
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('batch.show', [$batch->batch->id]) }}"
                                                    class="btn btn-sm btn-primary">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="4">No Batches Assigned Yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>My Recent Activities</h6>
                        {{-- <p class="text-sm">
                            <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                            <span class="font-weight-bold">24%</span> this month
                        </p> --}}
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            @forelse ($activities as $activity)
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient">notifications</i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $activity->description }}
                                        </h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient">notifications</i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">No Activity Yet</h6>
                                        {{-- <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{$activity->created_at->diffForhums}}</p> --}}
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('brand_ambassador_access')
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <a href="{{ route('products.index') }}">
                    <div class="card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                <i class="material-icons opacity-10">weekend</i>
                            </div>

                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Merchandise</p>
                                <h4 class="mb-0">{{ count($productsbas) }}</h4>
                            </div>

                        </div>
                        <hr class="dark horizontal my-0">
                        <div class="card-footer p-3">
                            {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than lask
                        week</p> --}}
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">person</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">My Team Leader</p>
                            <h4 class="mb-0">{{ \DB::table('users')->where('role_id',3)->where('county_id',Auth::user()->county_id)->value('name') }}</h4>
                            <p class="text-sm mb-0">{{ \DB::table('users')->where('role_id',3)->where('county_id',Auth::user()->county_id)->value('email') }}</p>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than lask
                        month</p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">star</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Merchandise Issued Out</p>
                            <h4 class="mb-0">
                                {{ count(\DB::table('issue_products')->where('ba_id', Auth::id())->get()) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday
                    </p> --}}
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-header p-3 pt-2">
                        <div
                            class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                            <i class="material-icons opacity-10">weekend</i>
                        </div>
                        <div class="text-end pt-1">
                            <p class="text-sm mb-0 text-capitalize">Total Merchandise Batches</p>
                            <h4 class="mb-0">{{ count($batchesbas) }}</h4>
                        </div>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="card-footer p-3">
                        {{-- <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than
                        yesterday</p> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row mt-4">
            <div class="col-lg-4 col-md-6 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Website Views</h6>
                        <p class="text-sm ">Last Campaign Performance</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm"> campaign sent 2 days ago </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mt-4 mb-4">
                <div class="card z-index-2  ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-success shadow-success border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 "> Daily Sales </h6>
                        <p class="text-sm "> (<span class="font-weight-bolder">+15%</span>) increase in today sales.
                        </p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm"> updated 4 min ago </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mb-3">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                            <div class="chart">
                                <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-0 ">Completed Tasks</h6>
                        <p class="text-sm ">Last Campaign Performance</p>
                        <hr class="dark horizontal">
                        <div class="d-flex ">
                            <i class="material-icons text-sm my-auto me-1">schedule</i>
                            <p class="mb-0 text-sm">just updated</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row mb-4 mt-4">
            <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
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
                                                        <h6 class="mb-0 text-sm">{{ $batch->batch->batch_code }}</h6>
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
                                                        \DB::table('products')->where('products.batch_id', $batch->batch->id)->join('productbas', 'productbas.product_id', 'products.id')->where('productbas.assigned_to', Auth::id())->get(),
                                                    ) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">
                                                    {{ count(
                                                        \DB::table('issue_products')->where('batch_id', $batch->batch->id)->where('ba_id', Auth::id())->get(),
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
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header pb-0">
                        <h6>Merchandise Type Data</h6>
                        {{-- <p class="text-sm">
                            <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
                            <span class="font-weight-bold">24%</span> this month
                        </p> --}}
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-two-side">
                            @forelse ($categories as $category)
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $category->title }}</h6>
                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Total:
                                            {{ count(
                                                \DB::table('products')->select('*')->where('products.category_id', $category->id)->join('productbas', 'productbas.product_id', 'products.id')->where('productbas.assigned_to', Auth::id())->get(),
                                            ) }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">No Merchandise Types Assigned Yet
                                        </h6>
                                    </div>
                                </div>
                            @endforelse

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
            $('#batchTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Batches_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Batches_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
        $('#userTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('home') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'role', name: 'roles.title'},
                {data: 'created_at', name: 'created_at'},
            ],
            pageLength: 10,
            dom: 'lBfrtip',
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'users_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'users_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    'colvis'
                ]
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        const url_productsPerMonth = `{{ route('api.products.issued-per-month') }}`;
        const setBg = () => {
            const randomColor = "#" + Math.floor(Math.random() * 16777215).toString(16);
            return randomColor
        }
        async function fetchData() {
            let response_bar = await fetch(url_productsPerMonth);
            const res_bar = await response_bar.json();

            const labels_bar = [];
            const backgroundColor_bar = [];
            const data_bar1 = [];
            let opacity = 1.0;
            for (let i = 0; i < res_bar.data.length; i++) {
                let color = 'rgb(245, 39, 128,'
                color = color + ((opacity -= 0.1).toString()) + ')'
                //console.log(color)
                labels_bar.push(res_bar.data[i].month);
                data_bar1.push(res_bar.data[i].count);
                backgroundColor_bar.push(color);
            }



            const data_bar = {
                labels: labels_bar,
                datasets: [{
                    label: 'Merchandise Issued',
                    backgroundColor: backgroundColor_bar,
                    // borderColor: ['rgb(106, 255, 51)', 'rgb(255,66,51)', 'rgb(255, 189, 51 )'],
                    data: data_bar1,
                }]
            };

            const config_bar = {
                plugins: [ChartDataLabels],
                type: 'bar',
                data: data_bar,
                options: {
                    plugins: {
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: Math.round,
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
            };
            const myChart_bar = new Chart(
                document.getElementById('myChart-bar'),
                config_bar
            );
            // Pie Chart
            const url_pie = `{{ route('api.products.issued-per-type') }}`;
            let response_pie = await fetch(url_pie);
            const res_pie = await response_pie.json();

            const labels_pie = [];
            const backgroundColor_pie = [];
            const data_pie1 = [];
            let opacity_pie = 1.0;
            for (let i = 0; i < res_pie.data.length; i++) {
                let color_pie = 'rgb(39, 128, 245,'
                color_pie = color_pie + ((opacity_pie -= 0.1).toString()) + ')'
                // console.log(color_pie)
                labels_pie.push(res_pie.data[i].name);
                data_pie1.push(res_pie.data[i].count);
                backgroundColor_pie.push(color_pie);
            }



            const data_pie = {
                labels: labels_pie,
                datasets: [{
                    label: 'Merchandise Issued Per Type',
                    backgroundColor: backgroundColor_pie,
                    // borderColor: ['rgb(106, 255, 51)', 'rgb(255,66,51)', 'rgb(255, 189, 51 )'],
                    data: data_pie1,
                }]
            };

            const config_pie = {
                type: 'pie',
                data: data_pie,
                options: {
                    responsive: false,
                    maintainAspectRatio: true,
                }
            };

            const myChart_pie = new Chart(
                document.getElementById('myChart-pie'),
                config_pie
            );

            //Line Charts
            var speedCanvas = document.getElementById("myChart-lines");

            const url_lines = `{{ route('api.products.issued-per-type-per-month') }}`;
            let response_lines = await fetch(url_lines);
            const res_lines = await response_lines.json();


            //const dataset = [dataFirst,dataSecond]
            const label_lines = []
            const counts = []
            const labels = []
            // const data = {
            //     label: res_lines.data[0][j].type,
            //     data: res_lines.data[i][j].count,
            //     lineTension: 0,
            //     fill: false,
            //     borderColor: 'red'
            // };
            for (let i = 0; i < res_lines.data.length; i++) {
                labels.push(res_lines.data[i].month)

            }

            const dataElementLabel = []
            for (let i = 0; i < res_lines.data.length; i++) {
                var innerArrayLength = Object.keys(res_lines.data[i]).length;
                //console.log(innerArrayLength)
                for (let j = 0; j < innerArrayLength - 1; j++) {
                    dataElementLabel.push(res_lines.data[0][j].type)
                }
                break;

            }
            const dataFirst = {
                label: dataElementLabel,
                data: [0, 59, 75, 20, 20, 55, 40],
                lineTension: 0,
                fill: false,
                borderColor: 'red'
            };




            //console.log(labels)


            // for (let i = 0; i < res_lines.data.length; i++) {
            //     for (let j = 0; j < 2; j++) {
            //         const label = res_lines.data[i][j].type
            //         const counter = res_lines.data[i][j].count

            //         label_lines.push(label)
            //         counts.push(counter)
            //     }

            // }



            var speedData = {
                labels: labels,
                datasets: [dataFirst],
            };

            var chartOptions = {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 80,
                        fontColor: 'black'
                    }
                }
            };

            var lineChart = new Chart(speedCanvas, {
                type: 'line',
                data: speedData,
                options: chartOptions
            });
        }
        fetchData();
    </script>
@endsection
