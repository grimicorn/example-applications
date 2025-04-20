<app-form-accordion
class="mb3"
:collapsible="false"
header-title="Congratulations">
    <template slot="content">
        <p class="fw-bold">
            {{ $space->congratulations_message }}
        </p>
    </template>
</app-form-accordion>