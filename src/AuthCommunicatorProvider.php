<?php

namespace Almatar\Branding\Services;

use Almatar\Branding\Helpers\GuzzleClient;
use Almatar\Branding\Services\Communicators\AuthCommunicator;
use Illuminate\Support\ServiceProvider;

class AuthCommunicatorProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AuthCommunicator::class, function ($app) {
            return new AuthCommunicator(new GuzzleClient());
        });
    }
}