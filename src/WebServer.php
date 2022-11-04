<?php

namespace CaioMarcatti12\Web;

use CaioMarcatti12\Bean\Objects\BeanProxy;
use CaioMarcatti12\Factory\Annotation\Autowired;
use CaioMarcatti12\Router\Interfaces\RouterResponseInterface;
use CaioMarcatti12\Webserver\Interfaces\WebServerRunnerInterface;


class WebServer implements WebServerRunnerInterface
{
    #[Autowired]
    private WebServerRunnerInterface $webServerRunner;

    public function run(): void
    {
        BeanProxy::add(RouterResponseInterface::class, RouterResponseWeb::class);

        $this->webServerRunner->run();
    }
}