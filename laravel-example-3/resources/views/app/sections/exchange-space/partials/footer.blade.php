<div class="exchange-space-show-footer">
    @if($space->currentMember->is_seller)
        <app-exchange-space-delete-modal
        delete-route="{{
            route('exchange-spaces.destroy', ['id' => $space->id])
        }}"></app-exchange-space-delete-modal>
    @else
        <app-exchange-space-leave-modal
        :enable-exit-message="{{ $space->currentMember->is_buyer ? 'false' : 'true' }}"
        delete-route="{{ route('exchange-spaces.member.destroy', [
            'id' => $space->id,
            'm_id' => $space->currentMember->id,
        ]) }}"></app-exchange-space-leave-modal>
    @endif
</div>
