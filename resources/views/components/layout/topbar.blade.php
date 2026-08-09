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
            <form class="form-inline my-2 my-lg-0 search" action="{{ $searchAction ?? '' }}" method="GET">
                <input class="form-control mr-sm-2" type="search" name="q" placeholder="Search" aria-label="Search">
                <label for="search" class="active"><i class="material-icons search-icon">search</i></label>
                <a href="#" id="close-search-input"><i class="material-icons">close</i></a>
            </form>

            <ul class="navbar-nav ml-auto">
                <li class="d-md-block d-lg-none nav-item">
                    <a class="nav-link search-link" href="#"><i class="material-icons">search</i></a>
                </li>

                {{-- Notifikasi --}}
                <li class="nav-item dropdown d-none d-lg-block">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">notifications_none</i>
                        @if ($unreadNotificationCount > 0)
                            <span class="badge">{{ $unreadNotificationCount }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dd-notifications" aria-labelledby="navbarDropdown">
                        @forelse ($recentNotifications() as $notification)
                            <li>
                                <a href="#!">
                                    <div class="notification">
                                        <div class="notification-icon circle cyan">
                                            <i class="material-icons">notifications</i>
                                        </div>
                                        <div class="notification-text">
                                            <p>{{ $notification->message ?? '' }}</p>
                                            <span>{{ $notification->created_at?->diffForHumans() ?? '' }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="notification-drop-title">Tidak ada notifikasi baru</li>
                        @endforelse
                    </ul>
                </li>

                {{-- Toggle Right Sidebar (Chat & Settings) --}}
                <li class="nav-item">
                    <a class="nav-link right-sidebar-link" href="#"><i class="material-icons">more_vert</i></a>
                </li>

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