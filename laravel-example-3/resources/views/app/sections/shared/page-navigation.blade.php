@php
// Try to get the menu items based off of the section if it was supplied to the view.
// If the item should be hidden then an empty array can be supplied.
if (!isset($menuItems) and isset($section)) {
    $menuItems = isset($sidebarMenuItems[ $section ]['submenu']) ? $sidebarMenuItems[ $section ]['submenu'] : [];
}
@endphp
@if(isset($menuItems) and count($menuItems) > 1 and (!isset($disablePageNav) or !$disablePageNav))
<nav class="page-navigation hide-print">
    <ul class="list-unstyled page-navigation-menu clearfix">
        @foreach($menuItems as $item)
        <li
        style="width: {{ 100 / count($menuItems) }}%"
        class="{{ (isset($item['isActive']) and $item['isActive']) ? 'active' : '' }}">
            <a href="{{ $item['href'] }}">{{ $item['label'] }}</a>
        </li>
        @endforeach
    </ul>
</nav>
@endif
