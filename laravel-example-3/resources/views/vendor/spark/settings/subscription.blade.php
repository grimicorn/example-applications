<spark-subscription :user="user" :team="team" :billable-type="billableType" inline-template>
    <div>
        <div v-if="plans.length > 0">
            <!-- New Subscription -->
            <div v-if="needsSubscription">
                @include('spark::settings.subscription.subscribe')
            </div>
        </div>
    </div>
</spark-subscription>
