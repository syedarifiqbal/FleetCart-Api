<?php


namespace Arif\FleetCartApi\Http\Controllers;


use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    protected function responseWithError($message, $errors = [], $code = 422)
    {
        return response(['message' => $message, 'errors' => $errors], $code);
    }

    protected function response($data, $code = 200)
    {
        return response()->json($data, $code);
    }
}
