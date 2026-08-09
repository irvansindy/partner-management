<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    <meta name="keywords" content="admin,dashboard">
    <meta name="author" content="stacks">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Partner Management') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/pt/1713190566.jpg') }}">
    {{-- <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}"> --}}

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment-with-locales.min.js" integrity="sha512-4F1cxYdMiAW98oomSLaygEwmCnIP38pb4Kx70yQYqRwLVCs3DbRumfBq82T08g/4LJ/smbFGFpmeFlQgoDccgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ mix('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">

    <!-- Styles (app lama, tetap dipertahankan).
         SENGAJA di-load DI SINI (sebelum tema Alpha), bukan di paling akhir.
         Kalau di-load setelah alpha.min.css, rule global dari sini (mis.
         reset "a { text-decoration: underline }" dari AdminLTE/Bootstrap
         versi lama) MENIMPA style tema Alpha karena cascade CSS menang
         berdasarkan urutan saat specificity sama. -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet"/>

    <!-- Plugin Styles (template Alpha) -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/waves/waves.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/nvd3/nv.d3.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!-- select 2 -->
    <link href="{{ asset('assets/plugins/select2/css/select2-material.css') }}" rel="stylesheet">

    <!-- Theme Styles (template Alpha) — di-load PALING AKHIR supaya menang -->
    <link href="{{ asset('assets/css/alpha.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <style>

    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @stack('styles')
</head>
<body>

    {{-- Loader — tampil sebentar saat halaman pertama kali load --}}
    <div class="loader">
        <div class="loader__figure"></div>
    </div>

    {{-- App Container — struktur utama dari template Alpha.
         Sesuaikan isi sidebar/topbar di bawah dengan kebutuhan Anda,
         atau pecah jadi @include('partials.sidebar') / @include('partials.topbar')
         supaya app.blade.php tidak terlalu panjang. --}}
    <div class="alpha-app">

        <x-layout.topbar />

        {{-- Quick Search Results — statis dulu, belum terhubung ke fitur
             pencarian nyata. Tetap dipertahankan di DOM supaya alpha.min.js
             tidak error saat mencoba toggle panel ini (form search di topbar
             mereferensikan elemen ini). --}}
        <div class="search-results">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="search-results-header">
                            <h4>Quick Search Results</h4>
                            <a href="#" id="closeSearch"><i class="material-icons">close</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-layout.sidebar />

        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

        {{-- Right Sidebar (Chat & Settings) — statis/placeholder, belum
             terhubung ke fitur chat nyata. Dipertahankan supaya tombol
             "more_vert" di topbar (yang toggle panel ini) tidak error. --}}
        <div class="page-right-sidebar">
            <div class="page-right-sidebar-inner">
                <div class="right-sidebar-header">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="chat-tab" data-toggle="tab" href="#chat-content" role="tab">Chat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings-content" role="tab">Settings</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="sidebar-messages tab-pane fade show active" id="chat-content" role="tabpanel">
                        <p class="right-sidebar-heading">CHAT LIST</p>
                        <div class="chat-list">
                            {{-- TODO: isi dari data chat/user nyata kalau fitur ini akan dipakai --}}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="settings-content" role="tabpanel">
                        <p class="right-sidebar-heading">SYSTEM</p>
                        <div class="settings-list">
                            {{-- TODO: isi setting nyata kalau fitur ini akan dipakai --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="chat-sidebar">
            {{-- TODO: isi dari fitur chat nyata kalau akan dipakai.
                 Sengaja dikosongkan dulu, elemen ini tetap ada di DOM supaya
                 alpha.min.js tidak error saat mencoba toggle panel ini. --}}
        </div>

    </div><!-- App Container -->

    <!-- Javascripts -->
    <script src="{{ asset('assets/plugins/jquery/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/d3/d3.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/nvd3/nv.d3.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.time.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.symbol.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.resize.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <!-- select 2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/alpha.min.js') }}"></script>

    {{-- Script khusus per-halaman (mis. dashboard.js) ditaruh di view masing-masing
         lewat @push('scripts'), BUKAN di sini, supaya tidak ikut ter-load di
         semua halaman. Lihat contoh di resources/views/dashboard.blade.php --}}
    @stack('scripts')

</body>
</html>