<?php

namespace Amadeus;

use Amadeus\Database\MySQL\MySQL;
use Amadeus\Environment\ServerManager;
use Amadeus\IO\Logger;
use Amadeus\Config\Config;
use Amadeus\Network\WebSocketServer;

class Process
{
    private static $_BASE;
    private static $ServerManager;

    public static function init($_BASE)
    {
        self::$_BASE=$_BASE;
        Logger::register();
        Config::register();
        MySQL::register();
        WebSocketServer::register();

        self::$ServerManager = new ServerManager();

        Logger::printLine('Amadeus System Successfully Started', 233);
        WebSocketServer::start();
    }
    public static function getBase(){
        return self::$_BASE;
    }
    public static function getServerManager(){
        return self::$ServerManager;
    }
}