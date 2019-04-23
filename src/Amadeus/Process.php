<?php

namespace Amadeus;

use Amadeus\Database\MySQL\MySQL;
use Amadeus\Environment\ServerManager;
use Amadeus\Game\GameType;
use Amadeus\IO\Logger;
use Amadeus\Config\Config;
use Amadeus\Network\WebSocketServer;
use Amadeus\Plugin\PluginManager;
use Composer\Autoload\ClassLoader;

/**
 * Class Process
 * @package Amadeus
 */
class Process
{
    /**
     * @var
     */
    private static $_BASE;
    /**
     * @var
     */
    private static $Loader;
    /**
     * @var
     */
    private static $ServerManager;
    /**
     * @var
     */
    private static $MySQL;
    /**
     * @var
     */
    private static $WebSocketServer;
    /**
     * @var
     */
    private static $PluginManager;
    /**
     * @var
     */
    private static $GameType;

    /**
     * @param string $_BASE
     * @param ClassLoader $Loader
     * @return bool
     */
    public static function init(string $_BASE, ClassLoader $Loader): bool
    {
        self::$_BASE = $_BASE;
        self::$Loader = $Loader;
        Logger::register();
        Config::register();

        self::$MySQL = new MySQL();
        self::$WebSocketServer = new WebSocketServer();
        //self::$MySQL->newServer('/', 'pm', 1, 1, 1, 1, 1);
        self::$GameType = new GameType();
        self::$PluginManager = new PluginManager();
        self::$PluginManager->start();
        self::$ServerManager = new ServerManager();
        Logger::printLine('Amadeus System Successfully Started', Logger::LOG_SUCCESS);
        self::$WebSocketServer->start();
        return true;
    }

    /**
     * @return string
     */
    public static function getBase(): string
    {
        return self::$_BASE;
    }

    /**
     * @return ClassLoader
     */
    public static function getLoader(): ClassLoader
    {
        return self::$Loader;
    }

    /**
     * @return ServerManager
     */
    public static function getServerManager(): ServerManager
    {
        return self::$ServerManager;
    }

    /**
     * @return PluginManager
     */
    public static function getPluginManager(): PluginManager
    {
        return self::$PluginManager;
    }

    /**
     * @return MySQL
     */
    public static function getMySQL(): MySQL
    {
        return self::$MySQL;
    }

    /**
     * @return WebSocketServer
     */
    public static function getWebSocketServer(): WebSocketServer
    {
        return self::$WebSocketServer;
    }

    /**
     * @return GameType
     */
    public static function getGameType(): GameType
    {
        return self::$GameType;
    }
}