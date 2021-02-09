<?php

use \Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->middleware('api_cors')
    ->group(function () {

        Route::middleware('auth:api')
            ->group(function () {

                Route::post('/products/{product}/reviews', \Arif\FleetCartApi\Http\Controllers\Account\AccountReviewController::class . '@store');

                Route::prefix('me')->group(function () {
                    Route::get('/', \Arif\FleetCartApi\Http\Controllers\Auth\AuthController::class . '@me');
                    Route::get('/orders', \Arif\FleetCartApi\Http\Controllers\Account\OrderController::class . '@index');
                    Route::get('/orders/{id}', \Arif\FleetCartApi\Http\Controllers\Account\OrderController::class . '@index');
                    Route::get('/orders/recent', \Arif\FleetCartApi\Http\Controllers\Account\OrderController::class . '@recent');
                    Route::put('/', \Arif\FleetCartApi\Http\Controllers\Auth\AuthController::class . '@update_me');
                    Route::get('/reviews', \Arif\FleetCartApi\Http\Controllers\Account\AccountReviewController::class . '@index');
                    Route::get('/wishlist', \Arif\FleetCartApi\Http\Controllers\Account\WishlistController::class . '@index');
                    Route::post('/wishlist', \Arif\FleetCartApi\Http\Controllers\Account\WishlistController::class . '@toggle');
                    Route::post('/wishlist/store', \Arif\FleetCartApi\Http\Controllers\Account\WishlistController::class . '@store');
                    Route::delete('/wishlist/{id}', \Arif\FleetCartApi\Http\Controllers\Account\WishlistController::class . '@destroy');
                });
            });


        Route::post('/register', \Arif\FleetCartApi\Http\Controllers\Auth\AuthController::class . '@postRegister');
        Route::post('/login', \Arif\FleetCartApi\Http\Controllers\Auth\AuthController::class . '@postLogin');
        Route::post('/logout', \Arif\FleetCartApi\Http\Controllers\Auth\AuthController::class . '@postLogout');
        Route::post('/password/reset/token', \Arif\FleetCartApi\Http\Controllers\Auth\PasswordResetController::class . '@create');
        Route::post('/password/reset', \Arif\FleetCartApi\Http\Controllers\Auth\PasswordResetController::class . '@reset');
        Route::post('/contact', \Arif\FleetCartApi\Http\Controllers\ContactController::class . '@store');

        Route::get('/products', \Arif\FleetCartApi\Http\Controllers\ProductController::class . '@index');
        Route::get('/flash-sale-products', \Themes\Storefront\Http\Controllers\FlashSaleProductController::class . '@index');
        Route::get('/products/{slug}', \Arif\FleetCartApi\Http\Controllers\ProductController::class . '@show');
        Route::get('/categories', \Arif\FleetCartApi\Http\Controllers\CategoryController::class . '@index');
        Route::get('/settings', \Arif\FleetCartApi\Http\Controllers\SettingsController::class . '@index');
        Route::get('/countries', \Arif\FleetCartApi\Http\Controllers\CountriesController::class . '@index');
        Route::get('/payment-gateways', \Arif\FleetCartApi\Http\Controllers\PaymentGatewaysController::class . '@index');
        Route::post('/checkout', \Arif\FleetCartApi\Http\Controllers\CheckoutController::class . '@store');
//        Route::post('cart/coupon', 'CartCouponController@store')->name('cart.coupon.store');
    });
