<?php


namespace Amadeus\Network;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use Swoole\WebSocket\Server as Server;

/**
 * Class WebSocketServer
 * @package Amadeus\Network
 */
class WebSocketServer
{
    /**
     * @var Server
     */
    /**
     * @var mixed|string|Server
     */
    /**
     * @var mixed|string|Server
     */
    /**
     * @var mixed|string|Server
     */
    private
        $server,
        $server_ip,
        $server_port,
        $server_workers;

    /**
     * WebSocketServer constructor.
     */
    public function __construct()
    {
        $this->server_ip = Config::get('daemon_address');
        $this->server_port = Config::get('daemon_port');
        $this->server_workers = Config::get('daemon_workers');
        if (empty($this->server_ip) or empty($this->server_port) or empty($this->server_workers)) {
            Logger::printLine('Failed to start websocket server...shutting down!', Logger::LOG_FATAL);
        }
        $this->server = new Server($this->server_ip, $this->server_port, SWOOLE_BASE, SWOOLE_TCP);
        $this->server->set(array(
            'worker_num' => $this->server_workers
        ));
        $this->server->on('open', ['Amadeus\Network\Reactor', 'onOpen']);
        $this->server->on('message', ['Amadeus\Network\Reactor', 'onMessage']);
        $this->server->on('close', ['Amadeus\Network\Reactor', 'onClose']);
        Logger::PrintLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        Logger::PrintLine('Starting Websocket server', Logger::LOG_SUCCESS);
        $this->server->start();
        return true;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }
}