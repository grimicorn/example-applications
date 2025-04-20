@php
// Template Name: Styleguide
@endphp

@extends('layout.base')

@section('content')
    <div class="container mb-4">
        <h1 class="text-theme-1 text-5xl mb-8" >Styleguide</h1>
        <div class="flex mb-8">
            <div class="p-1 text-xs m-1 bg-grey text-white hidden xxl:block xxl:bg-success"><i class="fas fa-check"></i> screen-xxl</div>
            <div class="p-1 text-xs m-1 bg-grey xl:bg-success xxl:bg-grey text-white hidden xl:block"><i class="fas fa-check"></i> screen-xl</div>
            <div class="p-1 text-xs m-1 bg-grey lg:bg-success xl:bg-grey text-white hidden lg:block"><i class="fas fa-check"></i> screen-lg</div>
            <div class="p-1 text-xs m-1 bg-grey md:bg-success lg:bg-grey text-white hidden md:block"><i class="fas fa-check"></i> screen-md</div>
            <div class="p-1 text-xs m-1 bg-grey sm:bg-success md:bg-grey text-white hidden sm:block"><i class="fas fa-check"></i> screen-sm</div>
            <div class="p-1 text-xs m-1 bg-success sm:bg-grey text-white"><i class="fas fa-check"></i> Mobile <span class="sm:hidden">Only</span></div>
        </div>
        <ul>
            <li><a href="#type">Typography</a></li>
            <li><a href="#font-sizes">Font Sizes</a></li>
            <li><a href="#textlinks">Text Links</a></li>
            <li><a href="#buttons">Buttons</a></li>
            <li><a href="#colors">Colors</a></li>
            <li><a href="#search">Search Forms</a></li>
            <li><a href="#forms">Gravity Forms</a></li>
            <li><a href="#container">Content Container Width</a></li>
            <li><a href="#page-builder-modules">Page Builder Modules</a></li>
        </ul>
        <hr class="divider" />
        <h2 id="type" class="text-theme-1 mb-8">Typography</h2>
        <div class="lg:flex mb-8">
            <div class="flex-1">
                <h1 class="h1 mb-4">H1 Heading (.h1)</h1>
                <h1 class="h2 mb-4">H2 Heading (.h2)</h1>
                <h1 class="h3 mb-4">H3 Heading (.h3)</h1>
                <h1 class="h4 mb-4">H4 Heading (.h4)</h1>
                <h1 class="h5 mb-4">H5 Heading (.h5)</h1>
                <h1 class="h6 mb-4">H6 Heading (.h6)</h1>
            </div>

            <div class="flex-1">
                <ul>
                    <li>Unordered List Item 1</li>
                    <li>
                        Unordered List Item 2
                        <ol>
                            <li>Ordered List Sub Item 1</li>
                            <li>Ordered List Sub Item 2</li>
                            <li>Ordered List Sub Item 3</li>
                        </ol>
                    </li>
                    <li>
                        Unordered List Item 3
                        <ul>
                            <li>Unordered List Sub Item 1</li>
                            <li>Unordered List Sub Item 2</li>
                            <li>Unordered List Sub Item 3</li>
                        </ul>
                    </li>
                    <li>Unordered List Item 4</li>
                </ul>
            </div>
            <div class="flex-1">
                <ol>
                    <li>Ordered List Item 1</li>
                    <li>
                        Ordered List Item 2
                        <ul>
                            <li>Unordered List Sub Item 1</li>
                            <li>Unordered List Sub Item 2</li>
                            <li>Unordered List Sub Item 3</li>
                        </ul>
                    </li>
                    <li>
                        Ordered List Item 3
                        <ol>
                            <li>Ordered List Sub Item 1</li>
                            <li>Ordered List Sub Item 2</li>
                            <li>Ordered List Sub Item 3</li>
                        </ol>
                    </li>
                    <li>Ordered List Item 4</li>
                </ol>
            </div>
        </div>
        <div class="flex flex-wrap sm:flex-no-wrap">
            <p class="w-full sm:w-1/5">.leading-none</p>
            <p class="leading-none w-full sm:w-4/5 mb-8">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel ultrices turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam.
            </p>
        </div>

        <div class="flex flex-wrap sm:flex-no-wrap">
            <p class="w-full sm:w-1/5">.leading-tighter</p>
            <p class="leading-tighter w-full sm:w-4/5 mb-8">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel ultrices turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam.
            </p>
        </div>


        <div class="flex flex-wrap sm:flex-no-wrap">
            <p class="w-full sm:w-1/5">.leading-tight</p>
            <p class="leading-tight w-full sm:w-4/5 mb-8">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel ultrices turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam.        </p>
        </div>


        <div class="flex flex-wrap sm:flex-no-wrap">
            <p class="w-full sm:w-1/5">.leading-normal</p>
            <p class="leading-normal w-full sm:w-4/5 mb-8">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel ultrices turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam.        </p>
        </div>


        <div class="flex flex-wrap sm:flex-no-wrap">
            <p class="w-full sm:w-1/5">.leading-loose</p>
            <p class="leading-loose w-full sm:w-4/5 mb-8">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel ultrices turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam.        </p>
        </div>

        <div class="mb-8">
            <a href="#link" class="link">Link Text (.link)</a><br/>
            <strong class="font-bold">Strong Text (.font-bold)</strong><br/>
            <i class="italic">Italic Text (.italic)</i><br/>
            <span class=" font-sans">Font Sans (.font-sans)</span><br/>
            <span class=" font-serif">Font Sans (.font-serif)</span><br/>
        </div>
        <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel ultrices turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam.</p>
        <blockquote>This is an example of a blockquote element. A blockquote can be a testimonial, quote, or callout.</blockquote>
        <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum eu nibh vel lacus accumsan dapibus. Fusce sit amet malesuada turpis, vel facilisis eros. Donec venenatis vestibulum massa, in bibendum est cursus sed. Nunc vel ultrices turpis. Sed risus enim, convallis vitae nulla elementum, vehicula consequat augue. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin congue laoreet tellus quis mollis. Morbi eget turpis est. Fusce gravida at tortor ut pulvinar. Sed efficitur sem eget lacus vulputate, at blandit quam aliquam.</p>

        <hr class="divider" />
        <h2 id="font-sizes" class="text-theme-1 mb-8">Font Sizes</h2>
        <p class="text-xs mb-4">Text xs is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-sm mb-4">Text sm is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-base mb-4">Text base is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-lg mb-4">Text lg is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-xl mb-4">Text xl is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-2xl mb-4">Text 2xl is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-3xl mb-4">Text 3xl is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-4xl mb-4">Text 4xl is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <p class="text-5xl leading-none mb-4">Text 5xl is exactly this size. The quick onyx goblin jumps over the lazy dwarf</p>
        <hr class="divider" />
        <h2 id="textlinks" class="text-theme-1 mb-8">Text links</h2>
        <ul>
            <li>You can configure the color of text links using tailwind.js</li>
        </ul>
        <a href="#">This is a text link example.</a> This is what text <a href="#">links</a> look like.
        <hr class="divider mt-8" />
        <h2 id="buttons" class="text-theme-1 mb-8">Buttons</h2>
        <ul>
            <li>Basic sitewide button styles</li>
        </ul>
        @include('partials.button',[
            'button_link' => '#link',
            'button_text' => 'I am a button',
            'button_new_window' => 'false',
        ])
          @include('partials.button',[
            'button_link' => '#link',
            'button_text' => 'I am disabled',
            'button_new_window' => 'false',
            'class' => 'disabled'
        ])

        <hr class="divider mt-8" />
        <h2 id="colors" class="text-theme-1 mb-8">Colors</h2>
        <ul>
            <li>You can add, edit, or remove colors using tailwind.js</li>
        </ul>

        <div class="flex flex-wrap">
        <div class="overflow-hidden w-full sm:w-1/2 mb-4 sm:pr-4">
            <div class="text-white bg-theme-1 px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Theme1</span>
                <span class="font-normal opacity-75">#aa202c</span>
            </div>
            <div class="text-white bg-theme-2 px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Theme2</span>
                <span class="font-normal opacity-75">#D52837</span>
            </div>
            <div class="text-black bg-white px-6 py-3 text-sm font-semibold flex justify-between">
                <span>White</span>
                <span class="font-normal opacity-75">#FFFFFF</span>
            </div>
            <div class="text-black bg-grey-light px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Grey Light</span>
                <span class="font-normal opacity-75">#DAE1E7</span>
            </div>
            <div class="text-black bg-grey px-6 py-3 text-sm font-semibold flex justify-between flex justify-between">
                <span>Grey</span>
                <span class="font-normal opacity-75">#B8C2CC</span>
            </div>
            <div class="text-white bg-grey-dark px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Grey Dark</span>
                <span class="font-normal opacity-75">#8795A1</span>
            </div>
            <div class="text-white bg-black px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Black</span>
                <span class="font-normal opacity-75">#14181c</span>
            </div>
        </div>
        <div class="overflow-hidden w-full sm:w-1/2 mb-4 sm:pr-4">

            <div class="text-white bg-error px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Error</span>
                <span class="font-normal opacity-75">#AA202C</span>
            </div>
            <div class="text-white bg-warning px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Warning</span>
                <span class="font-normal opacity-75">#f7d200</span>
            </div>
            <div class="text-white bg-success px-6 py-3 text-sm font-semibold flex justify-between">
                <span>Success</span>
                <span class="font-normal opacity-75">#07b736</span>
            </div>
        </div>

        </div>
        <hr class="divider" />
        <h2 id="search" class="text-theme-1 mb-8">Search Form</h2>
        <div class="max-w-sm mb-8">
            <?php get_search_form() ?>
        </div>
        <hr class="divider" />
        <h2 id="forms" class="text-theme-1 mb-8">Gravity Forms</h2>
        <ul>
            <li>Shows labels and sub-labels by default</li>
            <li>Add class "hide-labels" in form settings to hide ALL labels.</li>
            <li>Add class "hide-sub-labels" to hide ONLY sub-labels.</li>
        </ul>
        <p class="mb-8">Example:</p>
        <div class="w-full md:w-4/5 xl:w-3/5 mb-8">
            {!! gravity_form(1,true,true) !!}
        </div>
        <hr class="divider" />
        <h2 id="container" class="text-theme-1">Content Container Width</h2>
    </div>
     <div class="bg-theme-1 relative py-8 my-8">
        <div class="container mb-8 text-center text-white text-xl">
            <p>This area is the width of the browser window.</p>
            <p>Background images need to be large enough to fill this area on large screens.</p>
        </div>
        <div class="container bg-grey text-black h-64 flex flex-col justify-center text-xl text-center">
            <p class="">This grey area is the width of the ".container" class.</p>
            <p>This class controls the width of content on every page.</p>
            <p>Right now this container is:</p>
            <p class="text-5xl font-bold">
                <span class="hidden xl:block">1200px wide</span>
                <span class="hidden lg:block xl:hidden">992px wide</span>
                <span class="hidden md:block lg:hidden">768px wide</span>
                <span class="hidden sm:block md:hidden">576px wide</span>
                <span class="sm:hidden">100% width</span>
            </p>
        </div>
    </div>
    <div class="container">
        <hr class="divider" />
        <h2 id="page-builder-modules" class="text-theme-1 mb-8">Page Builder Modules</h2>
    </div>
    {!! kindling_page_builder_loader() !!}
@endsection
