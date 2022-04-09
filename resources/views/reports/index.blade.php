@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
@section('content')
    @can('tb_access')
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
                        <p class="text-sm ">Total Count from all Brand Ambassadors</p>
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
        <div class="row mt-4" height="200">
            <div class="col-lg-12 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-success shadow-primary border-radius-lg py-3 pe-1 mr-5">
                            <h4 class="text-center">Report Categories</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="mb-0 ">Products Reports</h6>
                                <p class="text-sm ">Get Products Issued Out</p>
                                <p><a href="{{route('report.products')}}" class="btn btn-sm btn-primary">View Report</a></p>
                                <hr class="dark horizontal">
                                <div class="d-flex ">
                                    <i class="material-icons text-sm my-auto me-1">schedule</i>
                                    <p class="mb-0 text-sm"> Alltime </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0 ">Clients Reports</h6>
                                <p class="text-sm ">Total Products Issued Per Client</p>
                                <p><a href="{{route('report.clients')}}" class="btn btn-sm btn-primary">View Report</a></p>
                                <hr class="dark horizontal">
                                <div class="d-flex ">
                                    <i class="material-icons text-sm my-auto me-1">schedule</i>
                                    <p class="mb-0 text-sm"> Alltime </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0 ">Team Leaders Reports</h6>
                                <p class="text-sm ">Total Count form all Team Leaders</p>
                                <p><a href="{{ route('report.teamleaders') }}" class="btn btn-sm btn-primary">View Report</a></p>
                                <hr class="dark horizontal">
                                <div class="d-flex ">
                                    <i class="material-icons text-sm my-auto me-1">schedule</i>
                                    <p class="mb-0 text-sm"> Alltime </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    @can('client_access')
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
                        <p class="text-sm ">Total Count from all Brand Ambassadors</p>
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
        <div class="row mt-4" height="200">
            <div class="col-lg-12 mt-4 mb-4">
                <div class="card z-index-2 ">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                        <div class="bg-gradient-success shadow-primary border-radius-lg py-3 pe-1 mr-5">
                            <h4 class="text-center">Report Categories</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="mb-0 ">Products Reports</h6>
                                <p class="text-sm ">Get Products Issued Out</p>
                                <p><a href="{{route('report.products.client')}}" class="btn btn-sm btn-primary">View Report</a></p>
                                <hr class="dark horizontal">
                                <div class="d-flex ">
                                    <i class="material-icons text-sm my-auto me-1">schedule</i>
                                    <p class="mb-0 text-sm"> Alltime </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0 ">Product Type Reports</h6>
                                <p class="text-sm ">Total Products Issued Per Product Type</p>
                                <p><a href="{{route('report.clients')}}" class="btn btn-sm btn-primary">View Report</a></p>
                                <hr class="dark horizontal">
                                <div class="d-flex ">
                                    <i class="material-icons text-sm my-auto me-1">schedule</i>
                                    <p class="mb-0 text-sm"> Alltime </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-0 ">Sales Representatives Reports</h6>
                                <p class="text-sm ">Total Count form all Team Leaders</p>
                                <p><a href="{{ route('report.teamleaders') }}" class="btn btn-sm btn-primary">View Report</a></p>
                                <hr class="dark horizontal">
                                <div class="d-flex ">
                                    <i class="material-icons text-sm my-auto me-1">schedule</i>
                                    <p class="mb-0 text-sm"> Alltime </p>
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
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'role',
                        name: 'roles.title'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
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
                    // {
                    //     extend: 'pdfHtml5',
                    //     title: 'users_list',
                    //     exportOptions: {
                    //         columns: [0, 1, 2, 3, 4, 5]
                    //     }
                    // },
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
