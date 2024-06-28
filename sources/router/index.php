<?php
    namespace NikonovAlex\Framework\Router;
    use \NikonovAlex\Framework\HTTP;

    require_once 'routes.php';

    function makeRouter( Routes $routes ): callable {
        return fn ( HTTP\Request $request ): callable | false =>
            findRoute( $routes, $request );
    }