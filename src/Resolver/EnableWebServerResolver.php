<?php

namespace CaioMarcatti12\Web\Resolver;

use CaioMarcatti12\Webserver\Interfaces\WebServerRunnerInterface;
use ReflectionClass;
use CaioMarcatti12\Bean\Annotation\AnnotationResolver;
use CaioMarcatti12\Bean\Interfaces\ClassResolverInterface;
use CaioMarcatti12\Bean\Objects\BeanProxy;
use CaioMarcatti12\Web\Annotation\EnableWebServer;

#[AnnotationResolver(EnableWebServer::class)]
class EnableWebServerResolver  implements ClassResolverInterface
{
    public function handler(object &$instance): void
    {
        $reflectionClass = new ReflectionClass($instance);

        $attributes = $reflectionClass->getAttributes(EnableWebServer::class);

        /** @var EnableWebServer $attribute */
        $attribute = ($attributes[0]->newInstance());

        BeanProxy::add(WebServerRunnerInterface::class, $attribute->getAdapter());
    }
}