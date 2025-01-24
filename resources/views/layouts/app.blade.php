@php
    $baseUrl = asset('assest')."/";
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{$baseUrl}}images/favicon.ico">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ $baseUrl }}css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ $baseUrl }}css/style.css">
    <link rel="stylesheet" href="{{ $baseUrl }}css/responsive.css">
    @yield('extra_css')
</head>
<body>
    @yield('content')
    @yield('js')
    <script src="{{ $baseUrl }}js/bootstrap.bundle.min.js"></script>
</body>
</html>
