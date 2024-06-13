<?php

namespace NikonovAlex\Framework\Site;

interface Navigation {

    public function homeURL(): string;

    public function items(): array;

}