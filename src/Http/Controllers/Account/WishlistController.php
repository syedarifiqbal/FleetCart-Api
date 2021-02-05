<?php


namespace Arif\FleetCartApi\Http\Controllers\Account;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class WishlistController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()
            ->wishlist()
            ->with('files')
            ->latest()
            ->paginate(request('per_page', config('fleetcart_api.per_page', 10)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store()
    {
        if (!auth()->user()->wishlistHas(request('productId'))) {
            auth()->user()->wishlist()->attach(request('productId'));
        }

        return response()->json([
            'message' => trans('fleetcart_api::validation.wishlist_added')
        ], Response::HTTP_CREATED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function toggle(): JsonResponse
    {
        if (auth()->user()->wishlistHas(request('productId'))) {
            return $this->destroy(request('productId'));
        }

        return $this->store();
    }

    /**
     * Destroy resources by the given id.
     *
     * @param string $productId
     * @return JsonResponse
     */
    public function destroy($productId)
    {
        auth()->user()->wishlist()->detach($productId);

        return response()->json([
            'message' => trans('fleetcart_api::validation.wishlist_deleted')
        ]);
    }
}
