<?php

namespace NikonovAlex\Framework\Template\CRUDSite;

use \NikonovAlex\Framework\HTTP;
use \NikonovAlex\Framework\Router;
use \NikonovAlex\Framework\CRUD;
use \NikonovAlex\Framework\Site;


function composeModules(
    $routes,
    $dbConnOptions,
    $site
) {
    return Site\makeSite(
        CRUD\makeCRUD(
            Router\makeRouter( $routes ),
            $dbConnOptions
        ),
        $site
    );
}

function makeRequestHandler( $modules ) {
    return fn ( $request ) =>
        ( fn ( $handler ) => !$handler
            ? HTTP\error404()
            : $handler( $request )
        )( $modules( $request ) );
}

function makeMain( $requestHandler ) {
    return function ( $_SERV, $_REQ ) use( $requestHandler ) {
        HTTP\printResponse(
            $requestHandler(
                HTTP\makeRequest( $_SERV, $_REQ )));
    };
}

function makeCRUDSite(
    $routes,
    $dbConnOptions,
    $site
): callable {
    return makeMain(
        makeRequestHandler(
            composeModules( $routes, $dbConnOptions, $site )
        )
    );
}