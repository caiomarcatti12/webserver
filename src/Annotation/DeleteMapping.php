<?php

namespace CaioMarcatti12\Webserver\Web\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Annotation\AliasFor;
use CaioMarcatti12\Core\Bean\Enum\BeanType;
use CaioMarcatti12\Webserver\Web\Annotation\RequestMapping;
use CaioMarcatti12\Webserver\Enum\RequestMethodEnum;

#[AliasFor(BeanType::REQUEST_MAPPING)]
#[Attribute(Attribute::TARGET_METHOD)]
class DeleteMapping extends RequestMapping
{
    protected string $route = '';
    protected RequestMethodEnum $requestMethod = RequestMethodEnum::DELETE;
}