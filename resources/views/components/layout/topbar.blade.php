{{-- resources/views/components/layout/topbar.blade.php --}}
{{-- Struktur & class PERSIS sama dengan demo asli (.page-header > nav.navbar),
     supaya alpha.min.js & alpha.min.css bekerja tanpa perlu modifikasi CSS. --}}
<div class="page-header">
    <nav class="navbar navbar-expand primary">
        <section class="material-design-hamburger navigation-toggle">
            <a href="javascript:void(0)" data-activates="slide-out" class="button-collapse material-design-hamburger__icon">
                <span class="material-design-hamburger__layer"></span>
            </a>
        </section>

        <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name', 'Alpha') }}</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                @include('adminlte::partials.navbar.locale-switch')

                {{-- User dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ auth()->user()->name ?? 'Guest' }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
