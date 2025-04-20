<?php

namespace Vms\Styleguide;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Illuminate\Http\Request;
use App\Enums\UserPermissionEnum;

class Styleguide extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('styleguide::navigation');
    }

    /**
     * @inheritDoc
     */
    public function authorize(Request $request)
    {
        return $request->user()->hasPermissionTo(UserPermissionEnum::VIEW_STYLE_GUIDE);
    }
}
