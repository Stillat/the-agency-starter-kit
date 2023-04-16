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
        Statamic::style('app', 'cp.css');
        Statamic::script('app', 'cp.js');

        $this->addProjectNav();
    }

    protected function addProjectNav()
    {
        Nav::extend(function ($nav) {
            $nav->content(__('site.project_board'))
                ->route('project-board')
                ->icon(file_get_contents(resource_path('svg/board.svg')));
        });
    }
}
