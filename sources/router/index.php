<?php
    namespace NikonovAlex\Framework\Router;
    use \NikonovAlex\Framework\HTTP;

    require_once 'routes.php';

    function makeRouter( Routes $routes, string $prefix = '' ): callable {
        return fn ( HTTP\Request $request ): callable | false =>
            findRoute( $routes, $prefix, $request );
    }