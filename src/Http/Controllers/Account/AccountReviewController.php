<?php


namespace Arif\FleetCartApi\Http\Controllers\Account;


use Arif\FleetCartApi\Http\Controllers\BaseController;
use Arif\FleetCartApi\Http\Requests\StoreReviewRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Product\Entities\Product;

class AccountReviewController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $reviews = auth()->user()
            ->reviews()
            ->with('product.files')
            ->whereHas('product')
            ->paginate(request('per_page', config('fleetcart_api.per_page', 10)));

        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $productId
     * @param StoreReviewRequest $request
     * @return Response
     */
    public function store($productId, StoreReviewRequest $request)
    {
        if (! setting('reviews_enabled')) {
            return $this->responseWithError(["Review is not enable to this platform."], Response::HTTP_EXPECTATION_FAILED);
        }

        return Product::findOrFail($productId)
            ->reviews()
            ->create([
                'reviewer_id' => auth()->id(),
                'rating' => $request->rating,
                'reviewer_name' => $request->reviewer_name,
                'comment' => $request->comment,
                'is_approved' => setting('auto_approve_reviews', 0),
            ]);
    }
}
