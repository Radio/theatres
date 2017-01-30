<?php

namespace Theatres\Service\Providers;

use Silex\Application;

class Config implements \Silex\ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['config'] = $app->share(function() use ($app) {
            return new \Theatres\Core\Config($app['dir.config']);
        });
    }


    public function boot(Application $app)
    {

    }
}