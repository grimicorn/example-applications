@extends('layouts.app')

@section('content')
    @include('partials.app-title', [
        'title' => $page->site->name,
        'title_link' => route('sites.show', [
            'site' => $page->site,
        ]),
        'subtitle' => $page->path,
        'subtitle_link' => $page->url,
        'subtitle_link_target' => '_blank',
    ])

    @if(!$baseline)
        <alert data-type="info" :data-dismissible="false">
            No snapshots found for this page.
        </alert>
    @else

        <div class="clearfix mb-8 -mx-4 flex">
            <div class="w-1/2 float-left px-4 flex flex-col">
                <h3 class="mb-4">Baseline</h3>
                <div class="shadow rounded overflow-hidden">
                    {{ $baseline->media('snapshot')->first() }}
                </div>
            </div>

            <div class="w-1/2 float-left px-4 flex flex-col">
                <h3 class="mb-4">Latest</h3>

                @if($latest)
                    <div class="shadow rounded overflow-hidden mb-4">
                        {{ $latest->media('snapshot')->first() }}
                    </div>
                    <form
                        action="{{ route('site.pages.baseline.update', [
                            'snapshot' => $latest,
                        ]) }}"
                        method="POST"
                    >
                        @method('PATCH')
                        @csrf
                        <button
                            type="submit"
                            class="button text-sm"
                        >Set as Baseline</button>
                    </form>
                @else
                    <div
                        class="shadow rounded overflow-hidden flex items-center justify-center h-full"
                    >
                        Latest Snapshot Does Not Exist
                    </div>
                @endif
            </div>
        </div>

        @if($latest)
            <div>
                <h3 class="mb-4 flex items-end leading-none">

                    Difference
                    <span
                        class="mx-1 text-base {{ $latest->over_threshold ? 'text-danger' : 'text-brand' }}"
                    >
                        ({{ $latest->differenceForDisplay }}%)
                    </span>

                    @if($latest->over_threshold)
                    <icon
                        class="text-danger"
                        data-name="exclamation-outline"
                        data-icon-class="w-4 h-4 fill-current"
                    ></icon>
                    @endif
                </h3>
                <div class="shadow rounded overflow-hidden">
                    {{ $latest->getFirstMedia('difference') }}
                </div>
            </div>
        @endif
    @endif
@endsection
