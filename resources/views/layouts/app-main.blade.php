@php
    $baseUrl = asset('assest')."/";
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{$baseUrl}}images/favicon.ico">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{ $baseUrl }}css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preload stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <!-- Include flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">




    {{--start multiple select2 --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> --}}
    {{--end multiple select2 --}}

    <link href="{{ $baseUrl }}plugins/bower_components/custom-select/custom-select.css" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ $baseUrl }}plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" /> --}}
    {{-- <link rel="stylesheet" href="{{ $baseUrl }}custom/plugins/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{ $baseUrl }}custom/plugins/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="{{ $baseUrl }}custom/plugins/multi-select.css"> --}}


    <link rel="stylesheet" href="{{ $baseUrl }}custom/css/toastr.min.css">
    <link rel="stylesheet" href="{{ $baseUrl }}css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ $baseUrl }}css/style.css">
    <link rel="stylesheet" href="{{ $baseUrl }}css/responsive.css">
    <link rel="stylesheet" href="{{ $baseUrl }}custom/css/custom.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
    @yield('extra_css')
</head>

<body class="block_model">
    <div id="loaderOverlay">
        <div class="loaderContent">
            <div class="spinner"></div>
            <p>Please wait...</p>
        </div>
    </div>


    <div class="main-container">
        <!-- Sidebar start -->
        @include('include.left-sidebar')
        <!-- Sidebar end -->

        <div class="home-section">
            <!-- header start -->
            @include('include.header')
            <!-- header end -->

            <!-- mainContent section start -->
            @yield('content')
            <!-- mainContent section end -->
        </div>

    </div>
    @yield('modal')

    <script src="{{ $baseUrl }}js/jquery-3.7.1.min.js"></script>
    <script src="{{ $baseUrl }}js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.2/js/dataTables.bootstrap5.js"></script>
    {{--start multiple select2 --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    {{--end multiple select2 --}}

    <script src="{{ $baseUrl }}plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>
    {{-- <script src="{{ $baseUrl }}plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script> --}}
    {{-- <script src="{{ $baseUrl }}custom/plugins/custom-select.min.js"></script>
    <script src="{{ $baseUrl }}custom/plugins/bootstrap-select.min.js"></script>
    <script src="{{ $baseUrl }}custom/plugins/bootstrap-tagsinput.min.js"></script>
    <script src="{{ $baseUrl }}custom/plugins/jquery.multi-select.js"></script> --}}


    <script src="{{ $baseUrl }}custom/js/toastr.min.js"></script>
    <script src="{{ $baseUrl }}js/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/de.js"></script>
    <script src="{{ $baseUrl }}js/custom.js"></script>

    <script>
        jQuery(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $(".select2").select2();
        });
    </script>
    @yield('js')
</body>

</html>