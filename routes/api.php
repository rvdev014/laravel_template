<?php

use App\Helpers\RouteHelper;
use Illuminate\Support\Facades\Route;

//404 not found endpoint
Route::fallback(function () {
    if (RouteHelper::checkRouteWithMethod()) {
        return response()->json(['message' => 'Method is not Allowed!'], 405);
    }
    return response()->json(['message' => 'Endpoint is not Found!'], 404);
});
