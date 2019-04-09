<?php


namespace Amadeus\Network;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use Swoole\WebSocket\Server as Server;

class WebSocketServer
{
    private static
        $server,
        $server_ip,
        $server_port,
        $server_workers;

    public static function register()
    {
        self::$server_ip = Config::get('daemon_address');
        self::$server_port = Config::get('daemon_port');
        self::$server_workers = Config::get('daemon_workers');
        if (empty(self::$server_ip) or empty(self::$server_port) or empty(self::$server_workers)) {
            Logger::printLine('Failed to start websocket server...shutting down!', 6);
        }
        self::$server = new Server(self::$server_ip, self::$server_port, SWOOLE_BASE, SWOOLE_TCP);
        self::$server->set(array(
            'worker_num' => self::$server_workers
        ));
        self::$server->on('open', ['Amadeus\Network\WebSocketCallback', 'onOpen']);
        self::$server->on('message', ['Amadeus\Network\WebSocketCallback', 'onMessage']);
        self::$server->on('close', ['Amadeus\Network\WebSocketCallback', 'onClose']);
        Logger::PrintLine('Successfully registered', 233);
    }

    public static function start()
    {
        Logger::PrintLine('Starting Websocket server', 233);
        self::$server->start();
        return true;
    }
}