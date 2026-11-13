@php
    $hasChildren = !empty($menu['children']) && count($menu['children']);
    $menuIsActive = $isMenuActive($menu);
@endphp

<li class="{{ $menuIsActive ? 'active-page' : '' }}">
    <a href="{{ !empty($menu['url']) ? url($menu['url']) : 'javascript:void(0)' }}"
       class="waves-effect waves-grey {{ $isActive($menu['url']) ? 'active' : '' }} {{ $hasChildren && $menuIsActive ? 'shown-menu' : '' }}"
       @if (empty($menu['url'])) onclick="return false;" @endif>

        @if ($depth === 0 && !empty($menu['icon']))
            <i class="{{ $menu['icon'] }}"></i>
        @endif

        {{ trans()->has('settings.menus.' . $menu['name']) ? __('settings.menus.' . $menu['name']) : $menu['name'] }}

        @if ($hasChildren)
            <i class="material-icons sub-arrow">keyboard_arrow_right</i>
        @endif
    </a>

    @if ($hasChildren)
        <ul class="accordion-submenu list-unstyled">
            @foreach ($menu['children'] as $child)
                @include('components.layout.sidebar-menu-item', ['menu' => $child, 'isActive' => $isActive, 'depth' => $depth + 1])
            @endforeach
        </ul>
    @endif
</li>
