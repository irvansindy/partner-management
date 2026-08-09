<li>
    <a href="{{ !empty($menu['url']) ? url($menu['url']) : 'javascript:void(0)' }}"
       class="waves-effect waves-grey {{ $isActive($menu['url']) ? 'active' : '' }}"
       @if (empty($menu['url'])) onclick="return false;" @endif>

        @if ($depth === 0 && !empty($menu['icon']))
            <i class="{{ $menu['icon'] }}"></i>
        @endif

        {{ $menu['name'] }}

        @if (!empty($menu['children']) && count($menu['children']))
            <i class="material-icons sub-arrow">keyboard_arrow_right</i>
        @endif
    </a>

    @if (!empty($menu['children']) && count($menu['children']))
        <ul class="accordion-submenu list-unstyled">
            @foreach ($menu['children'] as $child)
                @include('components.layout.sidebar-menu-item', ['menu' => $child, 'isActive' => $isActive, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>