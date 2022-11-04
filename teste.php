<?php

require_once __DIR__  . '/vendor/autoload.php';


#[\CaioMarcatti12\Webserver\Annotation\EnableWebServer(\CaioMarcatti12\Webserver\Adapter\SwooleAdapter::class)]
class App extends \CaioMarcatti12\Core\SpringApplication {

    public function start(): void
    {
        $teste = new \CaioMarcatti12\Core\Bean\ResolverLoader();
        $teste->load();

        \CaioMarcatti12\Core\Factory\InstanceFactory::resolveProperties($this);


        $webserver = new \CaioMarcatti12\WebserverServer();

        \CaioMarcatti12\Core\Factory\InstanceFactory::resolveProperties($webserver);
        $webserver->run();
    }
}
(new App())->start();
