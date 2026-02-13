<!doctype html>
<html lang="en" data-bs-theme="blue-theme">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'NORSU-GUIHULNGAN Queue System') }}</title>
        
        <!--favicon-->
        <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png">
        
        <!-- loader-->
        <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
        <script src="{{ asset('assets/js/pace.min.js') }}"></script>

        <!--plugins-->
        <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
        
        <!--bootstrap css-->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
        <!--main css-->
        <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/sass/main.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/sass/dark-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/sass/blue-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/sass/semi-dark.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/sass/bordered-theme.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/sass/responsive.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/toastr/latest/toastr.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/sweetalert2/latest/sweetalert2.min.css') }}" rel="stylesheet">
        
        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        @livewireStyles
        
        <!-- Stack for component-specific styles -->
        @stack('styles')
    </head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        {{ $slot }}
    </div>

    <!--bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    
    <script>
        // Patch main.js to safely handle PerfectScrollbar
        $(function () {
            "use strict";

            /* scrollar - with safety check */
            if (typeof PerfectScrollbar !== 'undefined') {
                if (document.querySelector(".notify-list")) {
                    new PerfectScrollbar(".notify-list");
                }
                if (document.querySelector(".search-content")) {
                    new PerfectScrollbar(".search-content");
                }
            }

            /* toggle button */
            $(".btn-toggle").click(function () {
                $("body").hasClass("toggled") ? ($("body").removeClass("toggled"), $(".sidebar-wrapper").unbind("hover")) : ($("body").addClass("toggled"), $(".sidebar-wrapper").hover(function () {
                    $("body").addClass("sidebar-hovered")
                }, function () {
                    $("body").removeClass("sidebar-hovered")
                }))
            })

            /* sidebar close */
            $(".sidebar-close").click(function () {
                $("body").addClass("toggled")
            })

            /* search */
            $(".search-control").focus(function () {
                $(".search-popup").addClass("show")
            })
            $(".search-close").click(function () {
                $(".search-popup").removeClass("show")
            })

            /* mobile search */
            $(".mobile-search-btn").click(function () {
                $(".search-popup").addClass("show")
            })
            $(".mobile-search-close").click(function () {
                $(".search-popup").removeClass("show")
            })

            /* metismenu */
            if (typeof MetisMenu !== 'undefined') {
                $("#sidenav").length && new MetisMenu("#sidenav")
                $("#menu").length && new MetisMenu("#menu")
            }
        });
    </script>
    
    <script src="{{ asset('assets/toastr/latest/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/sweetalert2/latest/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr-handler.js') }}"></script>
    
    <script>
        // Configure Toastr globally
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Show session flash messages as Toastr
        @if(session('status'))
            toastr.success('{{ session('status') }}');
        @endif

        @if(session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if(session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        @if(session('warning'))
            toastr.warning('{{ session('warning') }}');
        @endif

        @if(session('info'))
            toastr.info('{{ session('info') }}');
        @endif
    </script>

    @livewireScripts
    @stack('scripts')
</body>
</html>
