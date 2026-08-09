<div class="page-sidebar">
    <div class="page-sidebar-inner">

        <div class="page-sidebar-profile">
            <div class="sidebar-profile-image">
                <img src="{{ auth()->user()->avatar_url ?? asset('assets/images/avatars/avatar1.png') }}" alt="">
            </div>
            <div class="sidebar-profile-info">
                <a href="javascript:void(0);" class="account-settings-link">
                    <p>{{ auth()->user()->name ?? 'Guest' }}</p>
                    <span>{{ auth()->user()->email ?? '' }}</span>
                </a>
            </div>
        </div>

        <div class="page-sidebar-menu">
            <div class="sidebar-accordion-menu">
                <ul class="sidebar-menu list-unstyled">
                    @foreach ($menus as $menu)
                        @include('components.layout.sidebar-menu-item', ['menu' => $menu, 'isActive' => $isActive, 'depth' => 0])
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="sidebar-footer">
            <p class="copyright">{{ config('app.name', 'Pralon') }} &copy;</p>
        </div>

    </div>
</div>