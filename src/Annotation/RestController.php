<?php

namespace CaioMarcatti12\Web\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Enum\BeanType;
use CaioMarcatti12\Core\Annotation\AliasFor;

#[AliasFor(BeanType::CONTROLLER)]
#[Attribute(Attribute::TARGET_CLASS)]
class RestController extends Controller
{

}