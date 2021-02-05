<?php


namespace Arif\FleetCartApi\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Events\ProductViewed;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Http\Controllers\ProductSearch;
use Modules\Product\Http\Middleware\SetProductSortOption;
use Modules\Review\Entities\Review;

class ProductController extends Controller
{
    use ProductSearch;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(SetProductSortOption::class)->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Product $model
     * @param ProductFilter $productFilter
     * @return Response|JsonResponse
     *
     */
    public function index(Product $model, ProductFilter $productFilter)
    {
        request()->merge(['perPage' => request('per_page', config('fleetcart_api.per_page', 10))]);
        return $this->searchProducts($model, $productFilter);
    }

    /**
     * Show the specified resource.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show($slug) : JsonResponse
    {
        $product = Product::findBySlug($slug);
        $relatedProducts = $product->relatedProducts()->forCard()->get();
        $upSellProducts = $product->upSellProducts()->forCard()->get();
        $review = $this->getReviewData($product);

        event(new ProductViewed($product));

        return response()->json(compact('product', 'relatedProducts', 'upSellProducts', 'review'));
    }

    private function getReviewData(Product $product)
    {
        if (! setting('reviews_enabled')) {
            return;
        }

        return Review::countAndAvgRating($product);
    }
}
