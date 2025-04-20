@if(isset($spaces) and count($spaces) > 0)
<div>
    @foreach($spaces as $space)
    <div class="row">
        <div class="col-xs-12 mb2">
            <a
            class="text-bold lh-title fc-color4"
            href="{{ route('exchange-spaces.show', ['id' => $space->id]) }}">
                {{ $space->listing->title }}
            </a>
        </div>
    </div>
    <div class="{{ $loop->last ? '' : 'bb1'}} mb2 row">
        <div class="col-xs-6">
            <div class="fz-14 lh-title text-italic">
                {{ $space->title }}
            </div>
        </div>
        <div class="col-xs-4 pb1">
            <app-exchange-space-deal-status
            :space="{{ $space->toJson() }}"></app-exchange-space-deal-status>
        </div>

        <div class="col-xs-2 text-center pb1 pr1">
            @if(intval($space->notification_count > 0))
                <a
                href="{{
                    route('exchange-spaces.notifications.index', [
                        'id' => $space->id,
                        'unread' => 1,
                    ])
                }}"
                class="icon-count a-nd a-color2"
                >{{ $space->notification_count }}</a>
            @else
                <span class="icon-count">{{ $space->notification_count }}</span>
            @endif
        </div>
    </div>
    @endforeach
</div>
@else
<div class="flex pt2 pb1 pl2 pr1 justify-center">
    <strong>No Exchange Spaces added to your dashboard. Add Exchange Spaces by clicking the gauge icon on the <a href="/dashboard/exchange-spaces">Exchange Spaces landing page</a>.</strong>
</div>
@endif
