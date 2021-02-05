<?php


namespace Arif\FleetCartApi\Http\Controllers;


use Modules\Cart\Cart;
use Modules\Coupon\Checkers\AlreadyApplied;
use Modules\Coupon\Checkers\ApplicableCategories;
use Modules\Coupon\Checkers\ApplicableProducts;
use Modules\Coupon\Checkers\CouponExists;
use Modules\Coupon\Checkers\ExcludedCategories;
use Modules\Coupon\Checkers\ExcludedProducts;
use Modules\Coupon\Checkers\MaximumSpend;
use Modules\Coupon\Checkers\MinimumSpend;
use Modules\Coupon\Checkers\UsageLimitPerCoupon;
use Modules\Coupon\Checkers\UsageLimitPerCustomer;
use Modules\Coupon\Checkers\ValidCoupon;
use Modules\Coupon\Entities\Coupon;
use Predis\Pipeline\Pipeline;

class CartCouponController
{
    private $checkers = [
        CouponExists::class,
        AlreadyApplied::class,
        ValidCoupon::class,
        MinimumSpend::class,
        MaximumSpend::class,
        ApplicableProducts::class,
        ExcludedProducts::class,
        ApplicableCategories::class,
        ExcludedCategories::class,
        UsageLimitPerCoupon::class,
        UsageLimitPerCustomer::class,
    ];

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $coupon = Coupon::findByCode(request('coupon'));

        resolve(Pipeline::class)
            ->send($coupon)
            ->through($this->checkers)
            ->then(function ($coupon) {
                Cart::applyCoupon($coupon);
                $this->condition(new CartCondition([
                    'name' => $coupon->code,
                    'type' => 'coupon',
                    'target' => 'total',
                    'value' => $this->getCouponValue($coupon),
                    'order' => 2,
                    'attributes' => [
                        'coupon_id' => $coupon->id,
                    ],
                ]));
            });

        return Cart::instance();
    }
}
