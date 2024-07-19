<?php

namespace NikonovAlex\Framework\Site;

use \NikonovAlex\Framework\HTTP;

require_once 'site.php';
require_once 'navigation.php';
require_once 'navigation-item.php';

function handler( $handler, $request, $site ) {
    return fn ( $request, ... $passArgs ) =>
        $handler( $request, $site, ... $passArgs );
}

function handleRequest( $request, $router, $site ) {
    return ( fn ( $handler ) =>
        !$handler
            ? false
            : handler( $handler, $request, $site )
    )( $router( $request ) );
}

function makeSite( $router, $site ) {
    return fn ( $request ) =>
        handleRequest( $request, $router, $site );
}