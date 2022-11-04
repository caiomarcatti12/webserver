<?php

namespace CaioMarcatti12\Webserver;

use CaioMarcatti12\Core\Bean\Objects\BeanProxy;
use CaioMarcatti12\Core\Factory\Annotation\Autowired;
use CaioMarcatti12\Router\Interfaces\RouterResponseInterface;
use CaioMarcatti12\Router\Web\RouterWebLoader;
use CaioMarcatti12\Webserver\Interfaces\WebServerRunnerInterface;


class WebServer implements WebServerRunnerInterface
{
    #[Autowired]
    private WebServerRunnerInterface $webServerRunner;

    public function run(): void
    {
        BeanProxy::add(RouterResponseInterface::class, RouterResponseWeb::class);

        (new RouterWebLoader())->handler();

        $this->webServerRunner->run();
    }
}