<?php

namespace NikonovAlex\Framework\HTTP;

class Response {

    private $_status;
    private $_message;
    private $_headers;

    public function __construct( $status, $message, $headers = [] ) {
        $this->_status = $status;
        $this->_message = $message;
        $this->_headers = $headers;
    }

    public function status() {
        return $this->_status;
    }

    public function message() {
        return $this->_message;
    }

    public function headers() {
        return $this->_headers;
    }

}


function emptyResponse( $status ) {
    return new Response( $status, '' );
}

function success( $message ) {
    return new Response( 200, $message );
}

function error404( $message = '' ) {
    return new Response( 404, $message );
}

function redirect( $location, $temp = false ) {
    return new Response( $temp ? 302 : 301, '', [
        'Location' => $location,
    ] );
}

function printResponse( $response ) {
    http_response_code( $response->status() );
    foreach ( $response->headers() as $header => $value ) {
        header( "$header: $value" );
    }
    echo $response->message();
    exit();
}