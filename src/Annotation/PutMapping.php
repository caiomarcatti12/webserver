<?php

namespace CaioMarcatti12\Webserver\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Annotation\AliasFor;
use CaioMarcatti12\Core\Bean\Enum\BeanType;
use CaioMarcatti12\Webserver\Enum\RequestMethodEnum;

#[AliasFor(BeanType::REQUEST_MAPPING)]
#[Attribute(Attribute::TARGET_METHOD)]
class PutMapping extends RequestMapping
{
    protected string $path = '';
    protected RequestMethodEnum $requestMethod = RequestMethodEnum::PUT;

}