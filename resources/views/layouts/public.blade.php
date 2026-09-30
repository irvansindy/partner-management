<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Form Registration')</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/logo/icon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- File Input -->
    <link rel="stylesheet" href="{{ asset('css/cdn/file_input_min.css') }}">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="{{ asset('css/cdn/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cdn/leaflet_geocoder.css') }}">

    <style>
        :root {
            --public-ink: #202638;
            --public-muted: #70798b;
            --public-line: #e3e8ef;
            --public-paper: #fff;
            --public-canvas: #f3f5f8;
            --public-primary: #6558d3;
            --public-primary-dark: #5043bd;
            --public-primary-soft: #efedff;
            --public-success: #238768;
        }

        html {
            min-height: 100%;
        }

        body {
            background: var(--public-canvas);
            color: var(--public-ink);
            font-family: 'Roboto', 'Helvetica Neue', sans-serif;
            min-height: 100vh;
        }

        .public-topbar {
            background: #fff;
            border-bottom: 1px solid var(--public-line);
            min-height: 68px;
        }

        .public-topbar-inner {
            align-items: center;
            display: flex;
            justify-content: space-between;
            min-height: 68px;
        }

        .public-brand {
            align-items: center;
            color: var(--public-ink);
            display: inline-flex;
            font-size: 15px;
            font-weight: 700;
            gap: 12px;
            text-decoration: none;
        }

        .public-brand img {
            height: 38px;
            object-fit: contain;
            width: 42px;
        }

        .public-brand-caption {
            border-left: 1px solid var(--public-line);
            color: var(--public-muted);
            font-size: 12px;
            font-weight: 500;
            margin-left: 4px;
            padding-left: 14px;
        }

        .public-header-label {
            color: var(--public-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .public-main {
            margin: 0 auto;
            max-width: 1240px;
            min-height: calc(100vh - 142px);
            padding: 38px 24px 52px;
        }

        .public-footer {
            border-top: 1px solid var(--public-line);
            color: var(--public-muted);
            font-size: 12px;
            padding: 18px 24px;
            text-align: center;
        }

        @media (max-width: 576px) {
            .public-topbar,
            .public-topbar-inner {
                min-height: 60px;
            }

            .public-brand img {
                height: 32px;
                width: 36px;
            }

            .public-brand-caption,
            .public-header-label {
                display: none;
            }

            .public-main {
                padding: 24px 14px 36px;
            }
        }
    </style>
    @yield('css')
</head>
<body>
    <header class="public-topbar">
        <div class="container-fluid public-topbar-inner px-4">
            <a class="public-brand" href="/">
                <img src="{{ asset('uploads/logo/logo.png') }}" alt="Company logo">
                <span>PARTNER MANAGEMENT</span>
                <span class="public-brand-caption">External Partner Portal</span>
            </a>
            <span class="public-header-label">SECURE REGISTRATION</span>
        </div>
    </header>

    <main class="public-main">
        @yield('content')
    </main>

    <footer class="public-footer">
        &copy; {{ date('Y') }} Partner Management. All rights reserved.
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    </script>
    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- File Input -->
    <script src="{{ asset('js/cdn/file_input.js') }}"></script>
    <script src="{{ asset('js/cdn/file_input_sortable.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/cdn/leaflet_geocoder.js') }}"></script>

    @yield('js')
</body>
</html>