# FleetCart API

First install the package with the following command.
```angular2html
composer require arif/fleetcart-api
```

After that you have to open your `User` Model and add this Trait `HasApiToken`

Open `Modules\Core\Providers\CoreServiceProvider.php` and go to `$middleware` array and replace the namespace of `auth` middleware with this `\Arif\FleetCartApi\Http\Middleware\Authenticate::class`

