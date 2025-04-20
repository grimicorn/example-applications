<app-exchange-space-show-members
:groups="{{ json_encode($memberGroups) }}"
:current-member="{{ json_encode($space->currentMember) }}"
add-route="{{ route('exchange-spaces.member.store', ['id' => $space->id]) }}"
add-search-route="{{ route('exchange-spaces.member.index', ['id' => $space->id]) }}"></app-exchange-space-show-members>
