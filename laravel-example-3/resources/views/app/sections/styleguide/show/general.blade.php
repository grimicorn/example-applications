@php
$date = \Illuminate\Support\Carbon::now();
@endphp

@extends('layouts.styleguide')

@section('page-header')
@include('app.sections.shared.page-header', [
    'pageTitle' => 'Styleguide',
    'pageSubtitle' => 'General',
])
@endsection

@section('styleguide-content')

<h2>Confirmation</h2>
<styleguide-confirmation-example></styleguide-confirmation-example>
<div class="mb2"></div>

<sticky>
    <h2>Position Sticky "Polyfill"</h2>
</sticky>

<h2>Loader</h2>
<loader :loading="true" :cover="false"></loader>
<div class="mb2"></div>

<h2>LC Rating</h2>
<lc-rating
:data-percentage-complete="0.425"
class="text-center fz-64 fc-color5"></lc-rating>
<lc-rating :data-percentage-complete="0.40"></lc-rating>
<lc-rating :data-percentage-complete="0.45"></lc-rating>
<lc-rating :data-percentage-complete="0.499999999"></lc-rating>
<lc-rating :data-percentage-complete="0.5"></lc-rating>
<lc-rating :data-percentage-complete="0.501"></lc-rating>
<div class="mb2"></div>

<h2>Modal</h2>
<modal
button-label="Open Modal"
title="Modal title"
:auto-open="false"
class="mb3">
    <div v-cloak>
        Modal content
    </div>
</modal>

<h2>Avatar</h2>
<avatar
src="{{ $currentUser->photo_upload_url }}"
width="155"
height="155"
image-class="rounded"></avatar>

<div class="clear mb3"></div>

<h2>Timezone Date Conversions</h2>
<h3>Timezone - Date</h3>
<strong>UTC:</strong> {{ $date->format('n/j/Y') }}<br>
<strong>Current Timezone:</span>
<timezone-date date="{{ $date }}"></timezone-date>

<h3>Timezone - Date and Time</h3>
<strong>UTC:</strong> {{ $date->format('n/j/Y g:i a') }}<br>
<strong>Current Timezone:</span>
<timezone-datetime date="{{ $date }}"></timezone-datetime>

<br><br><br><br>

<h2>Tool Tip</h2>
<p>remove the <code>:auto-open="true"</code> to allow an interaction to open</p>
<tool-tip
direction="top"
:auto-open="true">
    Tool tip "top"
</tool-tip>

<br>

<tool-tip
:auto-open="true">
    Tool tip "right"
</tool-tip>

<br>

<tool-tip
direction="bottom"
:auto-open="true">
    Tool tip "bottom"
</tool-tip>

<br><br><br><br>

<tool-tip
direction="left"
:auto-open="true">
    Tool tip "left"
</tool-tip>

<br>

<tool-tip>
    Opens on interaction
</tool-tip>

<div class="clear mb3"></div>

<app-form-accordion
header-title="Form Accordion">
     <template slot="header-right">
        <app-listing-section-completion-bar
        :percentage-complete="0"></app-listing-section-completion-bar>
    </template>

    <template slot="content">
        Form accordion content
    </template>
</app-form-accordion>

<div class="clear mb3"></div>

<h2>Headers</h2>
<h1>Header 1</h1>
<h2>Header 2</h2>
<h3>Header 3</h3>
<h4>Header 4</h4>
<h5>Header 5</h5>
<h6>Header 6</h6>
<p>Lorem ipsum <a href="#">link</a>&nbsp;sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel <strong>bold</strong>&nbsp;turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, <em>italic</em>&nbsp;adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam. Nullam et velit ut nulla aliquam vehicula.</p>
<!-- <pre>Aliquam condimentum, libero sed consectetur maximus, justo eros fringilla purus, nec varius metus lectus sit amet odio. Sed feugiat nisi lacus, ut hendrerit tortor mollis quis. Etiam ut facilisis ante. Aenean vel quam quis ex consequat elementum id et tellus.</pre> -->
<p>In eget ligula id ex semper consectetur. Proin pulvinar placerat varius. Nunc vel elementum risus. Praesent efficitur maximus ultricies. Quisque lobortis dui ultricies orci aliquet scelerisque. Vivamus sit amet nisi laoreet erat finibus commodo. Duis vitae sapien tincidunt nulla sollicitudin sollicitudin vel fringilla eros. Nullam viverra eros at nisl volutpat, quis rhoncus neque auctor. <strong>Proin turpis tellus, vehicula nec ornare sed, accumsan in lacus.</strong> Phasellus aliquet, metus non pulvinar porta, nisl lacus sagittis nulla, a pharetra arcu lectus eu nunc. Curabitur <del>strikethrough</del>&nbsp;viverra ultrices. Curabitur augue odio, dignissim non felis sed, faucibus suscipit nisi. Curabitur ullamcorper, enim sed auctor accumsan, ipsum mauris egestas risus, sed tincidunt purus lorem et tortor.</p>
<hr>
<p style="text-align: left;">Aliquam quis magna nunc. Sed convallis dictum efficitur. Mauris volutpat velit in felis pharetra, nec bibendum urna cursus. Duis vitae nisi eget nunc luctus ultricies ornare sit amet arcu. In ut lorem eros. Nam sit amet molestie arcu. Vestibulum luctus convallis justo vel congue. Integer dui orci, venenatis ac accumsan in, rhoncus eu velit. Morbi a nisi arcu.</p>
<p style="text-align: center;">Aliquam quis magna nunc. Sed convallis dictum efficitur. Mauris volutpat velit in felis pharetra, nec bibendum urna cursus. Duis vitae nisi eget nunc luctus ultricies ornare sit amet arcu. In ut lorem eros. Nam sit amet molestie arcu. Vestibulum luctus convallis justo vel congue. Integer dui orci, venenatis ac accumsan in, rhoncus eu velit. Morbi a nisi arcu.</p>
<p style="text-align: right;">Aliquam quis magna nunc. Sed convallis dictum efficitur. Mauris volutpat velit in felis pharetra, nec bibendum urna cursus. Duis vitae nisi eget nunc luctus ultricies ornare sit amet arcu. In ut lorem eros. Nam sit amet molestie arcu. Vestibulum luctus convallis justo vel congue. Integer dui orci, venenatis ac accumsan in, rhoncus eu velit. Morbi a nisi arcu.</p>
<p style="text-align: left;">Quisque lobortis dui ultricies orci aliquet scelerisque.<br>
Vivamus sit amet nisi laoreet erat finibus commodo.<br>
Duis vitae sapien tincidunt nulla sollicitudin sollicitudin vel fringilla eros.</p>
<blockquote>
<p>Nullam viverra eros at nisl volutpat, quis rhoncus neque auctor.</p>
</blockquote>
<p style="text-align: left;">Proin turpis tellus, vehicula nec ornare sed, accumsan in lacus.<br>
In ut lorem eros. Nam sit amet molestie arcu.<br>
Aliquam quis magna nunc. Sed convallis dictum efficitur.</p>
<ul>
<li>Quisque lobortis dui ultricies orci aliquet scelerisque.</li>
<li>Vivamus sit amet nisi laoreet erat finibus commodo.</li>
<li>Duis vitae sapien tincidunt nulla sollicitudin sollicitudin vel fringilla eros.</li>
<li>Nullam viverra eros at nisl volutpat, quis rhoncus neque auctor.</li>
<li>Proin turpis tellus, vehicula nec ornare sed, accumsan in lacus.</li>
<li>In ut lorem eros. Nam sit amet molestie arcu.</li>
<li>Aliquam quis magna nunc. Sed convallis dictum efficitur.</li>
</ul>
<ol>
<li>Quisque lobortis dui ultricies orci aliquet scelerisque.</li>
<li>Vivamus sit amet nisi laoreet erat finibus commodo.</li>
<li>Duis vitae sapien tincidunt nulla sollicitudin sollicitudin vel fringilla eros.</li>
<li>Nullam viverra eros at nisl volutpat, quis rhoncus neque auctor.</li>
<li>Proin turpis tellus, vehicula nec ornare sed, accumsan in lacus.</li>
<li>In ut lorem eros. Nam sit amet molestie arcu.</li>
<li>Aliquam quis magna nunc. Sed convallis dictum efficitur.</li>
</ol>
@endsection
