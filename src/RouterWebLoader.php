<?php

namespace CaioMarcatti12\Webserver\Web;

use CaioMarcatti12\Core\Bean\Enum\BeanType;
use CaioMarcatti12\Core\Bean\Objects\BeanAlias;
use CaioMarcatti12\Core\ExtractPhpNamespace;
use CaioMarcatti12\Core\Launcher\Annotation\Launcher;
use CaioMarcatti12\Core\Launcher\Enum\LauncherPriorityEnum;
use CaioMarcatti12\Core\Launcher\Interfaces\LauncherInterface;
use CaioMarcatti12\Core\Validation\Assert;
use CaioMarcatti12\Webserver\Annotation\Controller;
use CaioMarcatti12\Webserver\Annotation\RequestMapping;
use CaioMarcatti12\Webserver\Objects\Route;

#[Launcher(LauncherPriorityEnum::BEFORE_LOAD_APPLICATION)]
class RouterWebLoader implements LauncherInterface
{
    public function handler(): void
    {
        $filesApplication = ExtractPhpNamespace::getFilesApplication();
        $filesFramework = ExtractPhpNamespace::getFilesFramework();

        $this->parseFiles(array_merge($filesApplication, $filesFramework));
    }

    private  function parseFiles(array $files): void{
        $attributesControllerList = BeanAlias::get(BeanType::CONTROLLER);

        foreach($files as $file){
            $reflectionClass = new \ReflectionClass($file);

            foreach($attributesControllerList as $attributeName){
                $reflectionAttributes = $reflectionClass->getAttributes($attributeName);

                if(Assert::isNotEmpty($reflectionAttributes)) {
                    /** @var \ReflectionAttribute $attribute */
                    $attribute = array_shift($reflectionAttributes);

                    /** @var Controller $instanceAttributeClass */
                    $instanceAttributeClass = $attribute->newInstance();
                    $routeClass = $instanceAttributeClass->getPath();

                    /** @var \ReflectionMethod $reflectionMethod */
                    foreach($reflectionClass->getMethods() as $reflectionMethod){

                        $attributesMappingList = BeanAlias::get(BeanType::REQUEST_MAPPING);

                        foreach($attributesMappingList as $attributeMappingName){
                            $reflectionAttributesMapping = $reflectionMethod->getAttributes($attributeMappingName);

                            if(Assert::isNotEmpty($reflectionAttributesMapping)) {
                                /** @var \ReflectionAttribute $attribute */
                                $attributeMapping = array_shift($reflectionAttributesMapping);

                                /** @var RequestMapping $instanceAttributeClass */
                                $instanceAttributeClass = $attributeMapping->newInstance();

                                $routeMethod = $instanceAttributeClass->getPath();
                                $routeComplete = $routeClass.$routeMethod;

                                $this->addRoute($routeComplete,
                                    $instanceAttributeClass->getRequestMethod()->value,
                                    $reflectionClass->getName(),
                                    $reflectionMethod->getName());
                            }
                        }
                    }
                }
            }
        }
    }

    private function addRoute(string $uri, string $httpMethod, string $file, $method): void {
        $route = new Route($uri, $httpMethod, $file, $method);
        RoutesWeb::add($route);
    }
}