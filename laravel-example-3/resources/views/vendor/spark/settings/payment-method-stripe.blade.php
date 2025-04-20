<spark-payment-method-stripe :user="user" :team="team" :billable-type="billableType" inline-template>
    <!-- Update Card -->
    @include('spark::settings.payment-method.update-payment-method-stripe')
</spark-payment-method-stripe>
