@if (Auth::check())
    @if(Auth::user()->photo_url)
    <img
    src="{{ Auth::user()->photo_url }}" class="spark-nav-profile-photo m-r-xs">
    @else
    <span class="spark-nav-profile-photo no-photo">
        {{ Auth::user()->initials }}
    </span>
    @endif
@endif
