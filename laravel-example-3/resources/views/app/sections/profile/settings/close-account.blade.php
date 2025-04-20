<div class="profile-settings-close-account">
    <h3>Close Your Account</h3>

    <p>
        Before you are able to close your account you need to complete the following.
    </p>

    <ul class="list-unstyled">
        <li>
            <i
            class="fa {{ auth()->user()->hasListings() ? 'fc-danger fa-times' : 'fa-check fc-success' }}"
            aria-hidden="true"></i>
            Delete all of your <a href="{{ route('listing.index') }}">businesses</a>
        </li>

        <li>
            <i
            class="fa {{ auth()->user()->hasActiveSubscription() ? 'fc-danger fa-times' : 'fa-check fc-success' }}"
            aria-hidden="true"></i>
            Cancel your <a href="{{ route('profile.payments.edit') }}">subscription</a>
        </li>
    </ul>

    <app-profile-close
    delete-route="{{
        route('profile.destroy')
    }}"
    :data-disabled="{{ auth()->user()->canCloseAccount() ? 'false' : 'true' }}"></app-profile-close>
</div>