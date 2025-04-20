@section('scripts')
    @if (Spark::billsUsingStripe())
        <script src="https://js.stripe.com/v2/"></script>
    @else
        <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
    @endif
@endsection

<spark-settings :user="user" :teams="teams" inline-template>
    <div class="spark-screen container">
        <!-- Tabs - If you remove this then there is a JS error -->
        <div class="hide">
            <div class="panel panel-default panel-flush">
                <div class="panel-heading">
                    Settings
                </div>

                <div class="panel-body">
                    <div class="spark-settings-tabs">
                        <ul class="nav spark-settings-stacked-tabs" role="tablist">
                            <!-- Profile Link -->
                            <li role="presentation">
                                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                                    <i class="fa fa-fw fa-btn fa-edit"></i>Profile
                                </a>
                            </li>

                            <!-- Teams Link -->
                            @if (Spark::usesTeams())
                                <li role="presentation">
                                    <a href="#{{str_plural(Spark::teamString())}}" aria-controls="teams" role="tab" data-toggle="tab">
                                        <i class="fa fa-fw fa-btn fa-users"></i>{{ ucfirst(str_plural(Spark::teamString())) }}
                                    </a>
                                </li>
                            @endif

                            <!-- Security Link -->
                            <li role="presentation">
                                <a href="#security" aria-controls="security" role="tab" data-toggle="tab">
                                    <i class="fa fa-fw fa-btn fa-lock"></i>Security
                                </a>
                            </li>

                            <!-- API Link -->
                            @if (Spark::usesApi())
                                <li role="presentation">
                                    <a href="#api" aria-controls="api" role="tab" data-toggle="tab">
                                        <i class="fa fa-fw fa-btn fa-cubes"></i>API
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Billing Tabs -->
            @if (Spark::canBillCustomers())
                <div class="panel panel-default panel-flush">
                    <div class="panel-heading">
                        Billing
                    </div>

                    <div class="panel-body">
                        <div class="spark-settings-tabs">
                            <ul class="nav spark-settings-stacked-tabs" role="tablist">
                                @if (Spark::hasPaidPlans())
                                    <!-- Subscription Link -->
                                    <li role="presentation">
                                        <a href="#subscription" aria-controls="subscription" role="tab" data-toggle="tab">
                                            <i class="fa fa-fw fa-btn fa-shopping-bag"></i>Subscription
                                        </a>
                                    </li>
                                @endif

                                <!-- Payment Method Link -->
                                <li role="presentation">
                                    <a href="#payment-method" aria-controls="payment-method" role="tab" data-toggle="tab">
                                        <i class="fa fa-fw fa-btn fa-credit-card"></i>Payment Method
                                    </a>
                                </li>

                                <!-- Invoices Link -->
                                <li role="presentation">
                                    <a href="#invoices" aria-controls="invoices" role="tab" data-toggle="tab">
                                        <i class="fa fa-fw fa-btn fa-history"></i>Invoices
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- Tabs - If you remove this then there is a JS error -->

        @if (Spark::canBillCustomers())
            <!-- Payment Method -->
            <div role="tabpanel" class="tab-pane" id="payment-method">
                <div v-if="user">
                    @include('spark::settings.payment-method')
                </div>
            </div>

            {{-- @if (Spark::hasPaidPlans())
                <!-- Subscription -->
                <div role="tabpanel" class="tab-pane" id="subscription">
                    <div v-if="user">
                        @include('spark::settings.subscription')
                    </div>
                </div>
            @endif --}}
        @endif
    </div>
</spark-settings>

