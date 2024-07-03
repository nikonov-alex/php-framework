<?php

namespace NikonovAlex\Framework\HTTP;

class Response {

    private int $_status;
    private string $_message;
    private array $_headers;

    public function __construct( int $status, string $message, array $headers = [] ) {
        $this->_status = $status;
        $this->_message = $message;
        $this->_headers = $headers;
    }

    public function status(): int {
        return $this->_status;
    }

    public function message(): string {
        return $this->_message;
    }

    public function headers(): string {
        return $this->_headers;
    }

}


function emptyResponse( int $status ): Response {
    return new Response( $status, '' );
}

function success( string $message ): Response {
    return new Response( 200, $message );
}

function error404( string $message = '' ): Response {
    return new Response( 404, $message );
}

function redirect( string $location, bool $temp = false ): Response {
    return new Response( $temp ? 302 : 301, '', [
        'Location' => $location,
    ] );
}

function printResponse( Response $response ) {
    http_response_code( $response->status() );
    foreach ( $response->headers() as $header => $value ) {
        header( "$header: $value" );
    }
    echo $response->message();
    exit();
}