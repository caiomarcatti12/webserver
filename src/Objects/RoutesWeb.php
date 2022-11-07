<?php

namespace CaioMarcatti12\Webserver;

use CaioMarcatti12\Core\Validation\Assert;
use CaioMarcatti12\Webserver\Exception\RouteDuplicatedException;
use CaioMarcatti12\Webserver\Objects\Route;

class RoutesWeb
{
    private static array $routes = [];

    public static function add(Route $route): void {

        if(self::getRoute($route->getRoute(), $route->getHttpMethod()))
            throw new RouteDuplicatedException($route);

        self::$routes[] = $route;
    }

    public static function getRoute(string $requestUri, string $requestMethod): ?Route
    {
        $route = array_filter(self::$routes, function (Route $route) use ($requestUri, $requestMethod) {
            return (
                Assert::equals($route->getRoute(), $requestUri) &&
                (Assert::equals($route->getHttpMethod(), $requestMethod) || Assert::isEmpty($route->getHttpMethod()))
            );
        });

        if(Assert::isEmpty($route)) return null;

        return array_shift($route);
    }

    public static function destroy(): void{
        self::$routes = [];
    }
}