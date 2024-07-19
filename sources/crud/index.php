<?php

    namespace NikonovAlex\Framework\CRUD;

    use \NikonovAlex\Framework\HTTP;

    class DBConnectionOptions {
        private $_DSN;
        private $_username;
        private $_password;

        public function __construct( $DSN, $username, $password ) {
            $this->_DSN = $DSN;
            $this->_username = $username;
            $this->_password = $password;
        }

        public function DSN() {
            return $this->_DSN;
        }

        public function username() {
            return $this->_username;
        }

        public function password() {
            return $this->_password;
        }

    }


    function connectDB( $dbConnOptions ) {
        return new \PDO( $dbConnOptions->DSN(), $dbConnOptions->username(), $dbConnOptions->password(), [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT
        ] );
    }

    function handler( $handler, $request, $pdo ) {
        return fn ( $request, ... $passArgs ) =>
            $handler( $request, $pdo, ... $passArgs );
    }


    function handleRequest( $request, $router, $dbConnOptions ) {
        return ( fn ( $handler ) =>
            !$handler
                ? false
                : ( fn ( $pdo ) =>
                    handler( $handler, $request, $pdo )
                )( connectDB( $dbConnOptions ) )
        )( $router( $request ) );
    }

    function makeCRUD( $router, $dbConnOptions ) {
        return fn ( $request ) =>
            handleRequest( $request, $router, $dbConnOptions );
    }