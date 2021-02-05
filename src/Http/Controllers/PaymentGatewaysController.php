<?php


namespace Arif\FleetCartApi\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Modules\Payment\Facades\Gateway;
use Modules\Support\Country;

class PaymentGatewaysController
{
    public function index(): JsonResponse
    {
        $gateways = Gateway::all();
        return response()->json($gateways);
    }
}
