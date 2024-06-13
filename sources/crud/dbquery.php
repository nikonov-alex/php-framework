<?php

namespace NikonovAlex\Framework\CRUD;

class DBQuery {

    private string $_query;

    public function __construct( string $query ) {
        $this->_query = $query;
    }

    public function query(): string {
        return $this->_query;
    }

}