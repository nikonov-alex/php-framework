<?php

namespace NikonovAlex\Framework\HTTP;

class Response {

    private int $_status;
    private string $_message;

    public function __construct( int $status, string $message ) {
        $this->_status = $status;
        $this->_message = $message;
    }

    public function status(): int {
        return $this->_status;
    }

    public function message(): string {
        return $this->_message;
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

function printResponse( Response $response ) {
    http_response_code( $response->status() );
    echo $response->message();
    exit();
}