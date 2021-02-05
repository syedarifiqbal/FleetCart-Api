<?php


namespace Arif\FleetCartApi\Http\Controllers;


use Modules\Category\Entities\Category;

class CategoryController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all()->nest();
    }

}
