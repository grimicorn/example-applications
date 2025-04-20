<div class="contact-sections contact-details">
    <div class="contact-details-inner">
        <div class="contact-section">
            <h3 class="contact-section-title">Mailing Address</h3>

            <address>
                {{ $address['line_1'] }}<br>
                {{ $address['line_2'] }}<br>
                {{ $address['city'] }}, {{ $address['state'] }} {{ $address['zipcode'] }}
            </address>
        </div>

        @if($email['info'])
        <div class="contact-section">
            <h3 class="contact-section-title">Email</h3>
            {{ $email['info'] }}
        </div>
        @endif

        @if($phone)
        <div class="contact-section">
            <h3 class="contact-section-title">Phone</h3>
            {{ $phone }}
        </div>
        @endif
    </div>
</div>
