<?php

namespace CaioMarcatti12\Web\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Enum\BeanType;
use CaioMarcatti12\Core\Annotation\AliasFor;
use CaioMarcatti12\Web\Enum\RequestMethodEnum;
use CaioMarcatti12\Web\Exception\InvalidNameRouteException;

#[AliasFor(BeanType::REQUEST_MAPPING)]
#[Attribute(Attribute::TARGET_METHOD)]
class PostMapping
{
    protected string $route = '';
    protected RequestMethodEnum $requestMethod = RequestMethodEnum::POST;

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