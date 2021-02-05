<?php


namespace Arif\FleetCartApi\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Modules\Support\Country;

class CountriesController
{
    public function index(): JsonResponse
    {
        $countries = Country::supported();
        return response()->json($countries);
    }
}
