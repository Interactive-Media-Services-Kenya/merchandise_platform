<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Server Error</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5 pt-5">
    <div class="alert alert-danger text-center">
        <h2 class="display-3">500</h2>
        <p class="display-5">Oops! Unauthorised User.</p>
        <a class="btn btn-success" href="{{ route('home') }}">
            Back Home
        </a>
    </div>
</div>
</body>
</html>


