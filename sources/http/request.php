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

}

function makeRequest( array $_SERV, array $_REQ ): Request {
    return new Request(
        $_SERV['REQUEST_URI'],
        $_SERV['REQUEST_METHOD'],
        $_REQ
    );
}