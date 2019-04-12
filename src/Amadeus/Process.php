<?php

namespace Amadeus;

use Amadeus\IO\Logger;
use Amadeus\Config\Config;
use Amadeus\Network\WebSocketServer;

class Process
{
    public static function init()
    {
        Logger::register();
        Config::register();

        $WebSocketServer = new WebSocketServer();

        Logger::printLine('Amadeus System Successfully Started', 233);

        $WebSocketServer->start();
    }
}