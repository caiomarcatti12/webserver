<?php

namespace CaioMarcatti12\Webserver\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Annotation\AliasFor;
use CaioMarcatti12\Core\Bean\Enum\BeanType;

#[AliasFor(BeanType::CONTROLLER)]
#[Attribute(Attribute::TARGET_CLASS)]
class Controller
{
    protected string $path = '';

    public function __construct(string $path = '')
    {
        $this->path = $this->normalize($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    private function normalize(string $path): string{
        if(!str_starts_with($path, '/')) $path = '/'.$path;
        if(!str_ends_with($path, '/')) $path = $path.'/';

        $path = str_replace('///', '/', $path);
        $path = str_replace('//', '/', $path);

        return $path;
    }
}