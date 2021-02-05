<?php


namespace Arif\FleetCartApi\Http\Controllers;


use Illuminate\Http\JsonResponse;

class SettingsController
{
    public function index(): JsonResponse
    {
        $settings = setting()->all();
        $settings = array_except($settings, config('fleetcart_api.exclude_settings', []));
        return response()->json($settings);
    }
}
