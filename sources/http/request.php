<?php

namespace NikonovAlex\Framework\HTTP;

class Request {

    private string $_path;
    private string $_method;

    public function __construct( string $path, string $method ) {
        $this->_path = $path;
        $this->_method = $method;
    }

    public function path(): string {
        return $this->_path;
    }

    public function method(): string {
        return $this->_method;
    }

}

function makeRequest( array $_SERV ): Request {
    return new Request(
        $_SERV['REQUEST_URI'],
        $_SERV['REQUEST_METHOD']
    );
}