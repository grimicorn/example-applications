<app-form-accordion
class="mb3"
:collapsible="false"
header-title="Deal Stages &amp; Key Milestones"
id="overlay_tour_stages_step">
    <template slot="content">
        <p>
            The deal stages below represent key milestones in the deal process. Sellers have sole discretion as to when to advance a deal to the next stage, but they should consider discussing stage updates with the other parties via the <a href="{{$space->id}}/diligence-center">Diligence Center</a>.
        </p>
        @isset($stages)
        <div class="pb text-center">
            @foreach($stages as $stage)
            @include('app.sections.exchange-space.partials.show-stages-item', $stage)
            @endforeach
        </div>
        @endisset
    </template>
</app-form-accordion>

