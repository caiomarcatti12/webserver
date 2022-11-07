<?php

namespace CaioMarcatti12\Webserver\Annotation;

use Attribute;
use CaioMarcatti12\Core\Bean\Annotation\AliasFor;
use CaioMarcatti12\Core\Bean\Enum\BeanType;
use CaioMarcatti12\Webserver\Enum\RequestMethodEnum;

#[AliasFor(BeanType::REQUEST_MAPPING)]
#[Attribute(Attribute::TARGET_METHOD)]
class RequestMapping
{
    protected string $path = '';
    protected RequestMethodEnum $requestMethod = RequestMethodEnum::GET;

    public function __construct(string $path)
    {
        $this->path = $this->normalize($path);
    }

    public function getPath(): string
    {
        return $this->path;
    }


    public function getRequestMethod(): RequestMethodEnum{
        return $this->requestMethod;
    }

    private function normalize(string $path): string{
        if(!str_starts_with($path, '/')) $path = '/'.$path;
        if(!str_ends_with($path, '/')) $path = $path.'/';

        $path = str_replace('///', '/', $path);
        $path = str_replace('//', '/', $path);

        return $path;
    }
}