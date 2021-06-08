<?php

namespace Almatar\Branding;

use Almatar\Branding\Services\BrandManager;
use Almatar\Branding\Services\Communicators\AuthCommunicator;
use Illuminate\Support\ServiceProvider;

class BrandingServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton(BrandManager::class, function ($app) {
            return new BrandManager(app(AuthCommunicator::class));
        });
    }

}