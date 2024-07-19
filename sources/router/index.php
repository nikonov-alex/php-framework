<?php
    namespace NikonovAlex\Framework\Router;
    use \NikonovAlex\Framework\HTTP;

    require_once 'routes.php';

    function makeRouter( $routes ) {
        return fn ( $request ) =>
            findRoute( $routes, $request );
    }