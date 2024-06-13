<?php

namespace NikonovAlex\Framework\Template\CRUDSite;

use \NikonovAlex\Framework\HTTP;
use \NikonovAlex\Framework\Router;
use \NikonovAlex\Framework\CRUD;
use \NikonovAlex\Framework\Site;


function composeModules(
    Router\Routes $routes,
    CRUD\DBConnectionOptions $dbConnOptions,
    Site\Site $site
): callable {
    return Site\makeSite(
        CRUD\makeCRUD(
            Router\makeRouter( $routes ),
            $dbConnOptions
        ),
        $site
    );
}

function makeRequestHandler( callable $modules ): callable {
    return fn ( HTTP\Request $request ): HTTP\Response =>
        ( fn ( callable | false $handler ) => !$handler
            ? HTTP\error404()
            : $handler( $request )
        )( $modules( $request ) );
}

function makeMain( callable $requestHandler ): callable {
    return function ( array $_SERV, array $_REQ ) use( $requestHandler ) {
        HTTP\printResponse(
            $requestHandler(
                HTTP\makeRequest( $_SERV, $_REQ )));
    };
}

function makeCRUDSite(
    Router\Routes $routes,
    CRUD\DBConnectionOptions $dbConnOptions,
    Site\Site $site
): callable {
    return makeMain(
        makeRequestHandler(
            composeModules( $routes, $dbConnOptions, $site )
        )
    );
}