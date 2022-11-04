<?php

namespace CaioMarcatti12\Web\Annotation;

use Attribute;
use CaioMarcatti12\Webserver\Enum\RequestMethodEnum;

#[Attribute(Attribute::TARGET_METHOD)]
class DeleteMapping
{
    protected string $route = '';
    protected RequestMethodEnum $requestMethod = RequestMethodEnum::DELETE;

    public function __construct(string $route = '')
    {
        if (empty($route)) throw new InvalidNameRouteException();

        $this->route = $route;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getRequestMethod(): RequestMethodEnum{
        return $this->requestMethod;
    }
}