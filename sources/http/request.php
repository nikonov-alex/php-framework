<?php

namespace NikonovAlex\Framework\HTTP;

class Request {

    private string $_path;
    private string $_method;
    private array $_params;

    public function __construct(
        string $path,
        string $method,
        array $params = []
    ) {
        $this->_path = $path;
        $this->_method = $method;
        $this->_params = $params;
    }

    public function path(): string {
        return $this->_path;
    }

    public function method(): string {
        return $this->_method;
    }

    public function params(): array {
        return $this->_params;
    }

    public function param( string $name ): mixed {
        return array_key_exists( $name, $this->_params )
            ? $this->_params[$name]
            : false;
    }

}

function getPath( string $url ): string {
    return ( fn ( $path ) =>
        '/' === $path
            ? $path
            : rtrim( $path, '/' )
    )( strtok( $url, '?' ) );
}

function formatParams( array $params ): array {
    return array_map( fn ( $value ) =>
        is_array( $value )
            ? formatParams( $value )
        : ( is_numeric( $value )
            ? $value + 0
        : $value ),
    $params );
}

function makeRequest( array $_SERV, array $_REQ ): Request {
    return new Request(
        getPath( !empty( $_SERV['PATH_INFO'] ) ? $_SERV['PATH_INFO'] : '/' ),
        $_SERV['REQUEST_METHOD'],
        formatParams( $_REQ )
    );
}