<?php

namespace CaioMarcatti12\Webserver;

use CaioMarcatti12\Core\Bean\Objects\BeanProxy;
use CaioMarcatti12\Core\Factory\Annotation\Autowired;
use CaioMarcatti12\Core\Launcher\Annotation\Launcher;
use CaioMarcatti12\Core\Launcher\Enum\LauncherPriorityEnum;
use CaioMarcatti12\Core\Launcher\Interfaces\LauncherInterface;
use CaioMarcatti12\Core\Modules\Modules;
use CaioMarcatti12\Core\Modules\ModulesEnum;
use CaioMarcatti12\Webserver\Interfaces\WebServerRunnerInterface;

#[Launcher(LauncherPriorityEnum::AFTER_LOAD_APPLICATION)]
class WebServer implements LauncherInterface
{
    #[Autowired]
    private WebServerRunnerInterface $webServerRunner;

    public function handler(): void
    {
        if(Modules::isEnabled(ModulesEnum::WEBSERVER))
            $this->webServerRunner->run();
    }
}