<?php

namespace NikonovAlex\Framework\Router;
use \NikonovAlex\Framework\HTTP;
use \NikonovAlex\Framework\Helpers;

class Route {

    private $_methods;

    public function __construct( $methods ) {
        $this->_methods = $methods;
    }

    public function methods() {
        return $this->_methods;
    }

}

function findMethod( $route, $method ) {
    return !empty ( $route->methods()[$method] )
        ? $route->methods()[$method]
        : false;
}




class Routes {

    private $_routes;

    public function __construct( $routes ) {
        $this->_routes = $routes;
    }

    public function routes() {
        return $this->_routes;
    }

}

function matchPath( $routes, $path ) {
    return Helpers\dictionaryFind( $routes->routes(),
        fn ( $routeRegexp ) => preg_match( $routeRegexp, $path ) );
}

function findRoute( $routes, $request ) {
    return ( fn ( $route ) =>
        !$route
            ? false
            : ( fn ( $handler ) =>
        !$handler
            ? false
            : $handler
        )( findMethod( $route, $request->method() ) )
    )( matchPath( $routes, $request->path() ) );
}