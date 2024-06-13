<?php

namespace NikonovAlex\Framework\Site;

class NavigationItem {
    private string $_title;
    private string $_icon;
    private string $_url;

    public function __construct( string $title, string $icon, string $url ) {
        $this->_title = $title;
        $this->_icon = $icon;
        $this->_url = $url;
    }

    public function title(): string {
        return $this->_title;
    }

    public function icon(): string {
        return $this->_icon;
    }

    public function URL(): string {
        return $this->_url;
    }
}