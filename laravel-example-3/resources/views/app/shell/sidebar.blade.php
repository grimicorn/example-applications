@isset($sidebarMenuItems)
{{--
You can add/edit menu items in the class/method below
App\Application\HasSidebarMenuItems::getSidebarMenuItems()
--}}
<div id="overlay_tour_step_2">
    <app-sidebar-navigation
    :menu-items="{{ json_encode($sidebarMenuItems) }}"
    section="{{ isset($section) ? $section : '' }}"></app-sidebar-navigation>

    @if (Auth::check() and Spark::developer(Auth::user()->email))
        <div class="text-center fz-12 pt1">v2.0.4</div>
    @endif
</div>
@endisset

@yield('sidebar')
