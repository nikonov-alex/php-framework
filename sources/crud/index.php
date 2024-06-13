<?php

    namespace NikonovAlex\Framework\CRUD;

    use \NikonovAlex\Framework\HTTP;

    require_once 'dbquery.php';

    class DBConnectionOptions {
        private string $_DSN;
        private string $_username;
        private string $_password;

        public function __construct( string $DSN, string $username, string $password ) {
            $this->_DSN = $DSN;
            $this->_username = $username;
            $this->_password = $password;
        }

        public function DSN() : string {
            return $this->_DSN;
        }

        public function username() : string {
            return $this->_username;
        }

        public function password() : string {
            return $this->_password;
        }

    }


    function connectDB( DBConnectionOptions $dbConnOptions ): \PDO {
        return new \PDO( $dbConnOptions->DSN(), $dbConnOptions->username(), $dbConnOptions->password() );
    }

    function handleGET( callable $handler, HTTP\Request $request, \PDO $pdo, ... $passArgs ): HTTP\Response {
        return $handler( $request, $pdo, ... $passArgs );
    }

    function execQuery( \PDO $pdo, DBQuery $dbquery ): HTTP\Response {
        return FALSE === $pdo->exec( $dbquery->query() )
            ? new HTTP\Response( 500, 'Error while executing database operation occurred' )
            : HTTP\success( 'OK' );
    }

    function handlePOST( callable $handler, HTTP\Request $request, \PDO $pdo, ... $passArgs ): HTTP\Response {
        return ( fn ( DBQuery | \Exception $dbquery ) =>
            $dbquery instanceof \Exception
                ? new HTTP\Response( 500, $dbquery->getMessage() )
            : execQuery( $pdo, $dbquery )
        )( $handler( $request, $pdo, ... $passArgs ) );
    }

    function handleCRUD( callable $handler, HTTP\Request $request, \PDO $pdo, ... $passArgs ): HTTP\Response {
        return 'GET' === $request->method()
            ? handleGET( $handler, $request, $pdo, ... $passArgs )
        : ( 'POST' === $request->method()
            ? handlePOST( $handler, $request, $pdo, ... $passArgs )
        : $handler( $request, ... $passArgs ) );
    }

    function handler( callable $handler, HTTP\Request $request, \PDO $pdo ): callable {
        return fn ( HTTP\Request $request, ... $passArgs ): HTTP\Response =>
            handleCRUD( $handler, $request, $pdo, ... $passArgs );
    }


    function handleRequest( HTTP\Request $request, callable $router, DBConnectionOptions $dbConnOptions ): callable | false {
        return ( fn ( callable | false $handler ): callable | false =>
            !$handler
                ? false
                : ( fn ( \PDO $pdo ): callable =>
                    handler( $handler, $request, $pdo )
                )( connectDB( $dbConnOptions ) )
        )( $router( $request ) );
    }

    function makeCRUD( callable $router, DBConnectionOptions $dbConnOptions ): callable {
        return fn ( HTTP\Request $request ): callable | false =>
            handleRequest( $request, $router, $dbConnOptions );
    }