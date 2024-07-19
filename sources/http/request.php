<?php

namespace NikonovAlex\Framework\HTTP;

class Request {

    private $_path;
    private $_method;
    private $_params;

    public function __construct(
        $path,
        $method,
        $params = []
    ) {
        $this->_path = $path;
        $this->_method = $method;
        $this->_params = $params;
    }

    public function path() {
        return $this->_path;
    }

    public function method() {
        return $this->_method;
    }

    public function params() {
        return $this->_params;
    }

    public function param( $name ) {
        return array_key_exists( $name, $this->_params )
            ? $this->_params[$name]
            : false;
    }

}

function getPath( $url ) {
    return ( fn ( $path ) =>
        '/' === $path
            ? $path
            : rtrim( $path, '/' )
    )( strtok( $url, '?' ) );
}

function formatParams( $params ) {
    return array_map( fn ( $value ) =>
        is_array( $value )
            ? formatParams( $value )
        : ( is_numeric( $value )
            ? $value + 0
        : $value ),
    $params );
}

function makeRequest( $_SERV, $_REQ ) {
    return new Request(
        getPath( !empty( $_SERV['PATH_INFO'] ) ? $_SERV['PATH_INFO'] : '/' ),
        $_SERV['REQUEST_METHOD'],
        formatParams( $_REQ )
    );
}