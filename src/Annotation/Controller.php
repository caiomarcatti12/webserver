<?php

namespace CaioMarcatti12\Webserver\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    protected string $route = '';

    public function __construct(string $route = '')
    {
        if(!str_starts_with($route,'/')) $route = '/'.$route;

        $this->route = $route;
    }

    public function getRoute(): string
    {
        return $this->route;
    }
}