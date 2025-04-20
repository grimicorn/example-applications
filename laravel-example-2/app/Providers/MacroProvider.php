<?php

namespace App\Providers;

use App\Board;
use Spatie\Menu\Laravel\Facades\Menu;
use Illuminate\Support\ServiceProvider;

class MacroProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Main Menu
         * @see https://docs.spatie.be/menu/v2/introduction/
         */
        Menu::macro('main', function () {
            $menu = Menu::new()
                ->action('JobsController@index', 'Filter View')
                ->setActiveFromRequest();

            Board::all()->each(function ($board) use (&$menu) {
                $menu->action('BoardsController@show', $board->name, $board);
            });

            return $menu;
        });
    }
}
