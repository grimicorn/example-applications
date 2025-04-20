@component('mail::message')
# {{ $site->name }} pages need review

@foreach ($pages as $page)
- {{ $page->path }} **({{ optional($page->snapshotConfigurations()->first())->getLatestSnapshot()->difference_for_display }}% difference from baseline)**
@endforeach

@component('mail::button', ['url' => $review_url])
Review
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
