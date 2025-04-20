<app-form-accordion
header-title="Payment Method"
class="mb3"
:collapsible="false">
    <template slot="content">
        @include('app.sections.profile.payments.update-form')
    </template>
</app-form-accordion>
