<?php

use \Illuminate\Support\Facades\Route;

Route::prefix('api')
//    ->middleware('cors')
    ->group(function () {

        Route::middleware('auth:api')
            ->group(function () {

                Route::post('/products/{product}/reviews', \Arif\FleetCartApi\Providers\Http\Controllers\Account\AccountReviewController::class . '@store');

                Route::prefix('me')->group(function () {
                    Route::get('/', \Arif\FleetCartApi\Providers\Http\Controllers\Auth\AuthController::class . '@me');
                    Route::get('/orders', \Arif\FleetCartApi\Providers\Http\Controllers\Account\OrderController::class . '@index');
                    Route::get('/orders/{id}', \Arif\FleetCartApi\Providers\Http\Controllers\Account\OrderController::class . '@index');
                    Route::get('/orders/recent', \Arif\FleetCartApi\Providers\Http\Controllers\Account\OrderController::class . '@recent');
                    Route::put('/', \Arif\FleetCartApi\Providers\Http\Controllers\Auth\AuthController::class . '@update_me');
                    Route::get('/reviews', \Arif\FleetCartApi\Providers\Http\Controllers\Account\AccountReviewController::class . '@index');
                    Route::get('/wishlist', \Arif\FleetCartApi\Providers\Http\Controllers\Account\WishlistController::class . '@index');
                    Route::post('/wishlist', \Arif\FleetCartApi\Providers\Http\Controllers\Account\WishlistController::class . '@toggle');
                    Route::post('/wishlist/store', \Arif\FleetCartApi\Providers\Http\Controllers\Account\WishlistController::class . '@store');
                    Route::delete('/wishlist/{id}', \Arif\FleetCartApi\Providers\Http\Controllers\Account\WishlistController::class . '@destroy');
                });

            });


        Route::post('/register', \Arif\FleetCartApi\Providers\Http\Controllers\Auth\AuthController::class . '@postRegister');
        Route::post('/login', \Arif\FleetCartApi\Providers\Http\Controllers\Auth\AuthController::class . '@postLogin');
        Route::post('/logout', \Arif\FleetCartApi\Providers\Http\Controllers\Auth\AuthController::class . '@postLogout');
        Route::post('/password/reset/token', \Arif\FleetCartApi\Providers\Http\Controllers\Auth\PasswordResetController::class . '@create');
        Route::post('/password/reset', \Arif\FleetCartApi\Providers\Http\Controllers\Auth\PasswordResetController::class . '@reset');
        Route::post('/contact', \Arif\FleetCartApi\Providers\Http\Controllers\ContactController::class . '@store');

        Route::get('/products', \Arif\FleetCartApi\Providers\Http\Controllers\ProductController::class . '@index');
        Route::get('/flash-sale-products', \Themes\Storefront\Http\Controllers\FlashSaleProductController::class . '@index');
        Route::get('/products/{slug}', \Arif\FleetCartApi\Providers\Http\Controllers\ProductController::class . '@show');
        Route::get('/categories', \Arif\FleetCartApi\Providers\Http\Controllers\CategoryController::class . '@index');
        Route::get('/settings', \Arif\FleetCartApi\Providers\Http\Controllers\SettingsController::class . '@index');
        Route::get('/countries', \Arif\FleetCartApi\Providers\Http\Controllers\CountriesController::class . '@index');
        Route::get('/payment-gateways', \Arif\FleetCartApi\Providers\Http\Controllers\PaymentGatewaysController::class . '@index');
        Route::post('/checkout', \Arif\FleetCartApi\Providers\Http\Controllers\CheckoutController::class . '@store');
        Route::post('cart/coupon', 'CartCouponController@store')->name('cart.coupon.store');
    });
