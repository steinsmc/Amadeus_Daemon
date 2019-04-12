<?php


namespace Amadeus\Network;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use Swoole\WebSocket\Server as Server;

class WebSocketServer
{
    private
        $server,
        $server_ip,
        $server_port,
        $server_workers;

    public function __construct()
    {
        $this->server_ip = Config::get('daemon_address');
        $this->server_port = Config::get('daemon_port');
        $this->server_workers = Config::get('daemon_workers');
        if (empty($this->server_ip) or empty($this->server_port) or empty($this->server_workers)) {
            Logger::printLine('Failed to start websocket server...shutting down!', 6);
        }
        $this->server = new Server($this->server_ip, $this->server_port, SWOOLE_BASE, SWOOLE_TCP);
        $this->server->set(array(
            'worker_num' => $this->server_workers
        ));
        $this->server->on('open', ['Amadeus\Network\Reactor', 'onOpen']);
        $this->server->on('message', ['Amadeus\Network\Reactor', 'onMessage']);
        $this->server->on('close', ['Amadeus\Network\Reactor', 'onClose']);
        Logger::PrintLine('Successfully constructed', 233);
    }

    public function start()
    {
        Logger::PrintLine('Starting Websocket server', 233);
        $this->server->start();
        return true;
    }

    public function getServer()
    {
        return $this->server;
    }
}