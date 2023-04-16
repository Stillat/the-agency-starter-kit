<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Statamic\Facades\CP\Nav;
use Statamic\Statamic;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register our custom Control Panel assets.
        Statamic::style('app', 'cp.css');
        Statamic::script('app', 'cp.js');

        // Add our custom Control Panel navigation item.
        $this->addProjectNav();
    }

    protected function addProjectNav()
    {
        // Uses the Nav facade to add a new navigation
        // item to the Control Panel.
        Nav::extend(function ($nav) {
            // Adds the "Project Board" item to the
            // "Content" section of the nav.
            $nav->content(__('site.project_board'))
                // The route method accepts the route
                // name that we want to link to. This
                // route is defined in routes/cp.php.
                ->route('project-board')
                // If pass a full SVG string to the icon method,
                // Statamic will just use whatever we supply
                // as the icon without attempting to look
                // for an SVG file in the resources/svg folder.
                ->icon(file_get_contents(resource_path('svg/board.svg')));
        });
    }
}
