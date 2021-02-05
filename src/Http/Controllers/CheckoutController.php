<?php

namespace Arif\FleetCartApi\Http\Controllers;

use Arif\FleetCartApi\Providers\Services\OrderService;
use Exception;
use Illuminate\Routing\Controller;
use Modules\Payment\Facades\Gateway;
use Modules\User\Services\CustomerService;
use Modules\Order\Http\Requests\StoreOrderRequest;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(['cart_not_empty', 'check_stock', 'check_coupon_usage_limit']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Modules\Order\Http\Requests\StoreOrderRequest $request
     * @param \Modules\User\Services\CustomerService $customerService
     * @param \Modules\Checkout\Services\OrderService $orderService
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request, CustomerService $customerService, OrderService $orderService)
    {
        if (auth()->guest() && $request->create_an_account) {
            $customerService->register($request)->login();
        }

        $order = $orderService->create($request);

        $gateway = Gateway::get($request->payment_method);

        try {
            $response = $gateway->purchase($order, $request);
        } catch (Exception $e) {
            $orderService->delete($order);

            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        return response()->json($response);
    }
}
