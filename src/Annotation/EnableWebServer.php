<?php

namespace CaioMarcatti12\Webserver\Annotation;

use Attribute;
use CaioMarcatti12\Webserver\Adapter\SwooleAdapter;

#[Attribute(Attribute::TARGET_CLASS)]
class EnableWebServer
{
    private string $adapter = '';

    public function __construct(string $adapter = SwooleAdapter::class)
    {
        $this->adapter = $adapter;
    }

    public function getAdapter(): string
    {
        return $this->adapter;
    }
}