<?php

    namespace NikonovAlex\Framework\CRUD;

    use \NikonovAlex\Framework\HTTP;


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

    function handleGet( callable $handler, HTTP\Request $request, \PDO $pdo, ... $passArgs ): HTTP\Response {
        return $handler( $request, $pdo, ... $passArgs );
    }

    function handleCRUD( callable $handler, HTTP\Request $request, \PDO $pdo, ... $passArgs ): HTTP\Response {
        return 'GET' === $request->method()
            ? handleGet( $handler, $request, $pdo, ... $passArgs )
        : $handler( $request, ... $passArgs );
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