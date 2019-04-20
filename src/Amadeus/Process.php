<?php

namespace Amadeus;

use Amadeus\Database\MySQL\MySQL;
use Amadeus\Environment\ServerManager;
use Amadeus\IO\Logger;
use Amadeus\Config\Config;
use Amadeus\Network\WebSocketServer;
use Amadeus\Plugin\PluginManager;
use Composer\Autoload\ClassLoader;

class Process
{
    private static $_BASE;
    private static $Loader;
    private static $ServerManager;
    private static $MySQL;
    private static $WebSocketServer;
    private static $PluginManager;

    public static function init(string $_BASE, ClassLoader $Loader): bool
    {
        self::$_BASE = $_BASE;
        self::$Loader = $Loader;
        Logger::register();
        Config::register();

        self::$MySQL = new MySQL();
        self::$WebSocketServer = new WebSocketServer();
        self::$ServerManager = new ServerManager();
        self::$MySQL->newServer('/', 'pm', 1, 1, 1, 1, 1);
        Logger::printLine('Loading Plugins', Logger::LOG_SUCCESS);
        self::$PluginManager = new PluginManager();
        self::$PluginManager->start();
        Logger::printLine('Amadeus System Successfully Started', Logger::LOG_SUCCESS);
        self::$WebSocketServer->start();
        return true;
    }

    public static function getBase(): string
    {
        return self::$_BASE;
    }

    public static function getLoader(): ClassLoader
    {
        return self::$Loader;
    }

    public static function getServerManager(): ServerManager
    {
        return self::$ServerManager;
    }

    public static function getPluginManager(): PluginManager
    {
        return self::$PluginManager;
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