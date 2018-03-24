<?php

namespace Cms\Framework\Foundation\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class CmsServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $providers = Collection::make($this->app->make('config')->get('cms.app.providers'))
            ->partition(function ($provider) {
                return Str::startsWith($provider, 'Illuminate\\');
            })->collapse()->toArray();

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }

        AliasLoader::getInstance($this->app->make('config')->get('cms.app.aliases', []))
            ->register();
    }
}