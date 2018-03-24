<?php

namespace Cms\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Symfony\Component\Finder\Finder;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {

        $this->mapAdminRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        $finder = new Finder;

        $finder->files()->in(__DIR__.'/../Routes/admin');
        foreach ($finder as $file) {
            // In case we need it later again
            $slug = str_slug($file->getBasename('.'. $file->getExtension()));

            Route::middleware(['web'])
                ->namespace('Cms\Modules\Core\Http\Controllers\Admin')
                ->prefix('admin')
                ->as("admin.")
                ->group($file->getPathname());
        }

    }
}
