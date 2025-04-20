<?php

namespace App\Http\ViewComposers;

use App\Support\HasStates;
use App\Support\HasSelects;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Support\HasBusinessCategories;
use App\Application\ViewData as ApplicationViewData;

class GlobalComposer
{
    use HasBusinessCategories, HasStates, HasSelects;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        // Add the current user.
        $user = Auth::check() ? Auth::user() : null;
        $view->with('currentUser', Auth::user());

        // Gives access to states for locations repeater input.
        $view->with('statesForSelect', $this->getStatesForSelect());

        if ($this->isApplication()) {
            (new ApplicationViewData)->get()->each(function ($value, $key) use ($view) {
                $view->with($key, $value);
            });
        }
    }

    protected function isApplication()
    {
        $current = Route::getFacadeRoot()->current();
        if (is_null($current)) {
            return false;
        }

        $isDashboard = ('dashboard' === trim($current->getPrefix(), '/'));
        $isSpark = (false !== strpos($current->uri, 'spark'));

        return ($isDashboard or $isSpark);
    }
}
