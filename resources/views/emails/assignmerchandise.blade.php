{{-- <!DOCTYPE html>
<html>
<head>
    <title>{{config('settings.app_name')}}</title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css')}}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.0.0') }}" rel="stylesheet" />
</head>
<body class="g-sidenav-show  bg-gray-200">
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container-fluid py-4">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header text-venter">
                        <h4>{{ $details['title'] }}</h4>
                    </div>
                    <div class="card-body">
                        <p>{{ $details['body'] }}</p>
                    </div>
                    <div class="card-footer">
                        <p>Thank you</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html> --}}
@component('mail::message')
# {{ $details['title'] }}
{{ $details['body'] }}
{{-- @component('mail::button', ['url' => $maildata['url']])
Verify
@endcomponent --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
