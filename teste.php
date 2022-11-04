<?php
require __DIR__ . '/vendor/autoload.php';


$swoole = new \CaioMarcatti12\Webserver\Adapter\SwooleAdapter();
$swoole->run();