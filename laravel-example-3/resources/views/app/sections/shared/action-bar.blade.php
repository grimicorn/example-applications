<div class="form-action-bar action-bar {{ isset($class) ? $class : '' }}">
    @isset($full)
    <div class="form-action-bar-full width-100">
        {{ $full }}
    </div>
    @endisset

    <div class="form-action-bar-left">
        @isset($left)
        {{ $left }}
        @endisset
    </div>

    <div class="form-action-bar-right">
        @isset($right)
        {{ $right }}
        @endisset
    </div>

    @isset($info)
    <div class="form-action-bar-info">
        {{ $info }}
    </div>
    @endisset
</div>

