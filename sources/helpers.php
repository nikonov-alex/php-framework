<?php

namespace NikonovAlex\Framework\Helpers;

function arrayFind( $array, $callback ) {
    foreach ( $array as $key => $value ) {
        if ( $callback( $key, $value ) ) {
            return $value;
        }
    }
    return false;
}

function dictionaryFind( $array, $callback ) {
    return arrayFind( $array, fn ( $key, $value ) => $callback( $key ) );
}

function dictionaryInsert( $array, $key, $value ) {
    $copy = $array;
    $copy[$key] = $value;
    return $copy;
}

function printTemplate( $template, $variables = [] ) {
    extract( $variables );
    ob_start();
    require $template;
    return ob_get_clean();
}

function arrayRemoveIndex( $array, $index ) {
    return !isset( $array[$index] )
        ? $array
        : ( function ( $newArray ) use ( $index ) {
            unset( $newArray[$index] );
            return $newArray;
        } )( $array );
}