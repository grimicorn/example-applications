{{-- You can use the php section to see all of the available variables.
Your meta name will translate to a variable.
If your meta key is my_meta_key the variable wil be $my_meta_key
You can delete this comment and the php section if you wish. --}}
@php
// dump($_page_builder_names); // Displays the variables available to the view from the module
// dump($_page_builder_modules); // Displays the current module data along with any other available data such as repeaters
@endphp

<div class="example-page-builder-module container mb-4">
Child
    <div class="row">
        <div class="col-sm-4">
            @if ($image_id)
                {!! wp_get_attachment_image($image_id, 'post-thumbnail') !!}
            @endif
        </div>

        <div class="col-sm-8">
            @if($title)
                <h3 class="example-page-builder-module-title">
                    {{ $title }}
                </h3>
            @endif

            @if ($content)
                <div class="example-page-builder-module-content">
                    {{ $content }}
                </div>
            @endif
        </div>
    </div>
</div>
