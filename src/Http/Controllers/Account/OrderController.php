<?php


namespace Arif\FleetCartApi\Http\Controllers\Account;


use Arif\FleetCartApi\Http\Controllers\BaseController;
use Arif\FleetCartApi\Http\Middleware\Authenticate;
use Arif\FleetCartApi\Http\Requests\StoreReviewRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Product\Entities\Product;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $orders = auth('api')->user()
            ->orders()
            ->latest()
            ->paginate(request('per_page', config('fleetcart_api.per_page', 10)));

        return response()->json($orders);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function recent() : JsonResponse
    {
        return response()->json(auth()->user()->recentOrders(5));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id) : JsonResponse
    {
        $order = auth()->user()
            ->orders()
            ->with(['products', 'coupon', 'taxes'])
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($order);
    }

}
