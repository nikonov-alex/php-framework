<?php

namespace NikonovAlex\Framework\Site;

class NavigationItem {
    private $_title;
    private $_icon;
    private $_url;

    public function __construct( $title, $icon, $url ) {
        $this->_title = $title;
        $this->_icon = $icon;
        $this->_url = $url;
    }

    public function title() {
        return $this->_title;
    }

    public function icon() {
        return $this->_icon;
    }

    public function URL() {
        return $this->_url;
    }
}