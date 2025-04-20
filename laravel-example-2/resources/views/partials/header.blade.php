<div class="bg-gray-100 mb-8 py-6">
    <div class="container mx-auto px-6 md:px-0">
        <div class="flex items-center">
            <div class="mr-6 navigation-logo">
                <a href="{{ route('jobs.index') }}" class="text-lg font-semibold no-underline">
                    <div class="flex justify-center items-center uppercase">
                        <div class="login-logo">
                            @include('partials.logo')
                        </div>
                        <span class="text-lg pl-1 pr-2 block mb-1 text-black">|</span>
                        <span class="text-sm font-bold leading-none text-black">VMS</span>
                    </div>
                </a>
            </div>
            <nav class="main-navigation flex-1 pr-3 flex justify-end">
                {!! Menu::main() !!}
            </nav>
            <div class="text-right">
                <header-quick-actions></header-quick-actions>
            </div>
        </div>
    </div>
</div>
