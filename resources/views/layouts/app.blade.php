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
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/metismenu/metisMenu.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/metismenu/mm-vertical.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css') }}">
        
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
    </head>

<body>
    <x-banner />

    <!--start header-->
    <header class="top-header">
        <nav class="navbar navbar-expand align-items-center gap-4">
            <div class="btn-toggle">
                <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
            </div>
            <div class="search-bar flex-grow-1">
                <div class="position-relative">
                    <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text" placeholder="Search">
                    <span class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                    <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
                </div>
            </div>
            <ul class="navbar-nav gap-1 nav-right-links align-items-center">
                <li class="nav-item d-lg-none mobile-search-btn">
                    <a class="nav-link" href="javascript:;"><i class="material-icons-outlined">search</i></a>
                </li>
                
                <li class="nav-item dropdown">
                    <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->profile_photo_url ?? asset('assets/images/avatars/11.png') }}" class="rounded-circle p-1 border" width="45" height="45" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                        <a class="dropdown-item gap-2 py-2" href="javascript:;">
                            <div class="text-center">
                                <img src="{{ Auth::user()->profile_photo_url ?? asset('assets/images/avatars/11.png') }}" class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                                <h5 class="user-name mb-0 fw-bold">{{ Auth::user()->name }}</h5>
                                <p class="text-secondary mb-0 small">{{ ucfirst(Auth::user()->role) }}</p>
                            </div>
                        </a>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.show') }}">
                            <i class="material-icons-outlined">person_outline</i>Edit Profile
                        </a>
                        <hr class="dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('logout') }}"
                               @click.prevent="$root.submit();">
                                <i class="material-icons-outlined">power_settings_new</i>Logout
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <!--end top header-->

    <!--start sidebar-->
    <aside class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div class="logo-icon">
                <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-img" alt="">
            </div>
            <div class="logo-name flex-grow-1">
                <h5 class="mb-0">{{ 'NORSU GUIHULNGAN' }}</h5>
            </div>
            <div class="sidebar-close">
                <span class="material-icons-outlined">close</span>
            </div>
        </div>
        <div class="sidebar-nav">
            <!--navigation-->
            @include('layouts.nav')
            <!--end navigation-->
        </div>
    </aside>
    <!--end sidebar-->

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@yield('breadcrumb-title', 'Dashboard')</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item">
                                <i class="material-icons-outlined me-1">@yield('breadcrumb-icon', 'dashboard')</i>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('breadcrumb-current', 'Dashboard')</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-info px-4 d-flex gap-2" onclick="window.location.reload();">
                            <i class="material-icons-outlined">refresh</i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            
            @if (isset($header))
                <div class="mb-3">
                    {{ $header }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>
    <!--end main wrapper-->

    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    <!--start footer-->
    <footer class="page-footer">
        <p class="mb-0">Copyright © {{ date('Y') }} {{ config('app.name', 'NORSU-GUIHULNGAN') }}. All right reserved.</p>
    </footer>
    <!--end footer-->

    <!--bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    
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

            /* menu */
            $(function () {
                $('#sidenav').metisMenu();
                $('#menu').metisMenu();
            });

            $(".sidebar-close").on("click", function () {
                $("body").removeClass("toggled")
            })

            /* dark mode button */
            $(".dark-mode i").click(function () {
                $(this).text(function (i, v) {
                    return v === 'dark_mode' ? 'light_mode' : 'dark_mode'
                })
            });

            $(".dark-mode").click(function () {
                $("html").attr("data-bs-theme", function (i, v) {
                    return v === 'dark' ? 'light' : 'dark';
                })
            })

            /* sticky header */
            $(document).ready(function () {
                $(window).on("scroll", function () {
                    if ($(this).scrollTop() > 60) {
                        $('.top-header .navbar').addClass('sticky-header');
                    } else {
                        $('.top-header .navbar').removeClass('sticky-header');
                    }
                });
            });

            /* search control */
            $(".search-control").click(function () {
                $(".search-popup").addClass("d-block");
                $(".search-close").addClass("d-block");
            });

            $(".search-close").click(function () {
                $(".search-popup").removeClass("d-block");
                $(".search-close").removeClass("d-block");
            });

            $(".mobile-search-btn").click(function () {
                $(".search-popup").addClass("d-block");
            });

            $(".mobile-search-close").click(function () {
                $(".search-popup").removeClass("d-block");
            });

            /* menu active */
            $(function () {
                for (var e = window.location, o = $(".metismenu li a").filter(function () {
                    return this.href == e
                }).addClass("").parent().addClass("mm-active"); o.is("li");) o = o.parent("").addClass("mm-show").parent("").addClass("mm-active")
            });
        });
    </script>
    
    <script>
        // Initialize PerfectScrollbar for sidebar if needed
        $(document).ready(function() {
            if (typeof PerfectScrollbar !== 'undefined' && document.querySelector('.sidebar-wrapper')) {
                try {
                    new PerfectScrollbar('.sidebar-wrapper');
                } catch(e) {
                    console.warn('PerfectScrollbar initialization failed:', e);
                }
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

        // Listen for Livewire events globally
        document.addEventListener('livewire:init', () => {
            // Profile saved events
            Livewire.on('saved', () => {
                toastr.success('Saved successfully!');
            });
            // Note: 'toastr' events are handled by toastr-handler.js
        });
    </script>
    
    <script src="{{ asset('assets/cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js') }}"></script>

    @stack('modals')
    @livewireScripts
    @stack('scripts')
</body>
</html>
