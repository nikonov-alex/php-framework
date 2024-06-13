<?php

namespace NikonovAlex\Framework\Site;

use \NikonovAlex\Framework\HTTP;

require_once 'site.php';
require_once 'navigation.php';

function handler( callable $handler, HTTP\Request $request, Site $site ): callable {
    return fn ( HTTP\Request $request, ... $passArgs ): HTTP\Response =>
        $handler( $request, $site, ... $passArgs );
}

function handleRequest( HTTP\Request $request, callable $router, Site $site ): callable | false {
    return ( fn ( callable | false $handler ): callable | false =>
        !$handler
            ? false
            : handler( $handler, $request, $site )
    )( $router( $request ) );
}

function makeSite( callable $router, Site $site ): callable {
    return fn ( HTTP\Request $request ): callable | false =>
        handleRequest( $request, $router, $site );
}