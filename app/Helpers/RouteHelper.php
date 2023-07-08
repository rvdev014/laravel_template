<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class RouteHelper
{
    public static function getRouteName(string $route): string
    {
        $route = Route::getRoutes()->getByName($route);
        return $route->uri();
    }

    public static function getAllRoutes(): array
    {
        return array_map(function ($route) {
            return $route->uri();
        }, Route::getRoutes()->get());
    }

    public static function isRouteExists(): bool
    {
        return in_array(request()->path(), self::getAllRoutes());
    }

    public static function getAllRouteMethods(): array
    {
        return array_map(function ($route) {
            return $route->methods();
        }, Route::getRoutes()->get());
    }

    public static function checkRouteWithMethod(): bool
    {
        if (!self::isRouteExists()) {
            return false;
        }

        $allMethods = self::getAllRouteMethods();
        $currentMethod = request()->method();
        foreach ($allMethods as $methods) {
            if (in_array($currentMethod, $methods)) {
                return true;
            }
        }
        return false;
    }
}
