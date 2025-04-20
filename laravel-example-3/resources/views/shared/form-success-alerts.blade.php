{{-- Success Alerts --}}
@if(session('success'))
<div v-cloak>
    <alert type="success" :timeout="6000">
        {{ session('status') }}
    </alert>
</div>
@endif
