<?php

namespace NikonovAlex\Framework\Router;
use \NikonovAlex\Framework\HTTP;
use \NikonovAlex\Framework\Helpers;

class Route {

    private array $_methods;

    public function __construct( array $methods ) {
        $this->_methods = $methods;
    }

    public function methods(): array {
        return $this->_methods;
    }

}

function findMethod( Route $route, string $method ): callable | false {
    return Helpers\dictionaryFind( $route->methods(),
        fn ( string $currentMethod ) => $currentMethod === $method );
}




class Routes {

    private array $_routes;

    public function __construct( array $routes ) {
        $this->_routes = $routes;
    }

    public function routes(): array {
        return $this->_routes;
    }

}

function matchPath( Routes $routes, string $path ): Route | false {
    return Helpers\dictionaryFind( $routes->routes(),
        fn ( string $routeRegexp ) => preg_match( $routeRegexp, $path ) );
}

function findRoute( Routes $routes, HTTP\Request $request ): callable | false {
    return ( fn ( Route | false $route ) =>
        !$route
            ? false
            : ( fn ( callable | false $handler ) =>
        !$handler
            ? false
            : $handler
        )( findMethod( $route, $request->method() ) )
    )( matchPath( $routes, $request->path() ) );
}