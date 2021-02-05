<?php

namespace Arif\FleetCartApi\Providers;

use Arif\FleetCartApi\Http\Middleware\ApiCors;
use Arif\FleetCartApi\Http\Middleware\Authenticate;
use FleetCart\Console\Commands\InstallFleetCartApi;
use Illuminate\Support\ServiceProvider;

class FleetCartApiServiceProvider extends ServiceProvider
{
    /**
     * Core module specific middleware.
     *
     * @var array
     */
    protected $middleware = [
        'auth' => Authenticate::class,
        'api_cors' => ApiCors::class,
    ];


    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/fleetcart_api.php', 'fleetcart_api');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'fleetcart_api');
        $this->loadViewsFrom(__DIR__ . '/../resources/view', 'fleetcart_api');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerInstallationCommand();

        $this->registerMiddleware();

        $this->setApiDriver();

        require __DIR__ . '/../routes/api.php';

        $this->publishes([
            __DIR__ . '/../config/fleetcart_api.php' => config_path('fleetcart_api.php'),
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/fleetcart_api'),
        ], 'translations');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * Register the filters.
     *
     * @return void
     */
    private function registerMiddleware()
    {
        foreach ($this->middleware as $name => $middleware) {
            $this->app['router']->aliasMiddleware($name, $middleware);
            $router = $this->app['router'];
            $router->pushMiddlewareToGroup('api', Authenticate::class);
        }
    }

    private function registerInstallationCommand()
    {
        if($this->app->runningInConsole())
            $this->commands([InstallFleetCartApi::class]);
    }

    private function setApiDriver()
    {
        $this->app['config']->set('auth.guards.api.driver', config('fleetcart_api.api_driver'));
    }
}
