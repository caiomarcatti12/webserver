<?php

namespace CaioMarcatti12\Webserver\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Annotation\AliasFor;
use CaioMarcatti12\Core\Bean\Enum\BeanType;

#[AliasFor(BeanType::CONTROLLER)]
#[Attribute(Attribute::TARGET_CLASS)]
class RestController extends Controller
{

}