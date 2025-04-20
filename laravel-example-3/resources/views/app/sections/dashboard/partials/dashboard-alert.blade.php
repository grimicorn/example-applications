@if($systemMessages)
<div class="container system-notification">
    <div class="row">
        @foreach($systemMessages as $message)
            @if($message->message)
                <div class="alert alert-{{$message->urgency}}">
                    {{$message->message}}
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif