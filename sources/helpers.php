<?php

namespace NikonovAlex\Framework\Helpers;

function arrayFind( Array $array, callable $callback ) {
    foreach ( $array as $key => $value ) {
        if ( $callback( $key, $value ) ) {
            return $value;
        }
    }
    return false;
}

function dictionaryFind( Array $array, callable $callback ) {
    return arrayFind( $array, fn ( string $key, $value ) => $callback( $key ) );
}

function dictionaryInsert( Array $array, string $key, $value ): Array {
    $copy = $array;
    $copy[$key] = $value;
    return $copy;
}

function printTemplate( string $template, Array $variables ): string {
    extract( $variables );
    ob_start();
    require_once $template;
    return ob_get_clean();
}