@php
$message->documents();
@endphp
<div class="bb2 pa2">
	<div class="flex pt2 pb1 pl2 pr1">
		<div class="pr2 flex-auto">
            <a href="{{ $message->user->profile_url }}">
                <avatar
                class="mr2"
                src="{{ $message->user->photo_thumbnail_small_url }}"
                width="44"
                height="44"
                initials="{{ $message->user->initials }}"
                :uses-two-factor="{{
                    $currentUser->uses_two_factor_auth ? 'true' :'false'
                }}"
                image-class="app-navbar-avatar rounded"></avatar>
            </a>
		</div>
		<div class="flex-11">
			<div>
				<div class="fz-24 fc-color6 text-bold pb1">
					<a href="{{ $message->user->profile_url }}"><span>{{ $message->user->name }}</span></a>
                    <span>&vert;</span>
                    <span class="fz-16 fc-color6 fw-regular">
                        {{ $message->creator_member->role_label }}
                    </span>
                </div>
				<div class="lh-copy pb1 breakword">
                    @if($message->deletedByAdmin())
                        <div class="text-italic">
                            {!! nl2br(e($message->body)) !!}
                        </div>
                    @else
                        {!! nl2br(e($message->body)) !!}
                    @endif
                </div>
				<div class="fc-color10 fz-14">
					<span class="text-italic">
                        Posted <timezone-datetime date="{{ $message->created_at }}"></timezone-datetime>
                    </span>
                    <span>&vert;</span>

                    @include('app.sections.shared.exchange-space.abuse-modal')

                    @if (auth()->user()->isDeveloper())
                    <span>&vert;</span>
                    <app-exchange-space-delete-message
                    :data-message-id="{{ $message->id }}"
                    data-message-body="{{ $message->body }}"></app-exchange-space-delete-message>
                    @endif

                    @include('app.sections.shared.exchange-space.message-documents',[
                        'message' => $message,
                    ])
				</div>
			</div>
		</div>
	</div>
</div>
