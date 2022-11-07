<?php

namespace CaioMarcatti12\Webserver\Objects;

use CaioMarcatti12\Webserver\Exception\InvalidArgumentRouteConstruct;

class Route
{
    private string $route;
    private string $httpMethod;
    private string $class;
    private string $classMethod;

    public function __construct(string $route, string $httpMethod, string $class, string $classMethod)
    {
        if(empty($class)) throw new InvalidArgumentRouteConstruct('class');
        if(empty($classMethod)) throw new InvalidArgumentRouteConstruct('classMethod');

        $this->route = str_replace('//', '/', $route);
        $this->httpMethod = $httpMethod;
        $this->class = $class;
        $this->classMethod = $classMethod;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getClassMethod(): string
    {
        return $this->classMethod;
    }
}