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
    private static $MySQL;
    private static $WebSocketServer;

    public static function init(string $_BASE): bool
    {
        self::$_BASE = $_BASE;
        Logger::register();
        Config::register();

        self::$MySQL = new MySQL();
        self::$WebSocketServer = new WebSocketServer();
        self::$ServerManager = new ServerManager();

        Logger::printLine('Amadeus System Successfully Started', Logger::LOG_SUCCESS);
        self::$WebSocketServer->start();
        return true;
    }

    public static function getBase(): string
    {
        return self::$_BASE;
    }

    public static function getServerManager(): ServerManager
    {
        return self::$ServerManager;
    }

    public static function getMySQL(): MySQL
    {
        return self::$MySQL;
    }

    public static function getWebSocketServer(): WebSocketServer
    {
        return self::$WebSocketServer;
    }
}