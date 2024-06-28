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

function makeRequest( array $_SERV, array $_REQ ): Request {
    return new Request(
        getPath( $_SERV['PATH_INFO'] ),
        $_SERV['REQUEST_METHOD'],
        $_REQ
    );
}