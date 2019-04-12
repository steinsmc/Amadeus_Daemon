<?php


namespace Amadeus\Network;

use Swoole\WebSocket\Server as Server;
use Amadeus\IO\Logger;

class Reactor
{
    public static function onOpen(Server $server, $request)
    {
        Logger::PrintLine('New Connection,fd: ' . $request->fd . ', ip: ' . $server->getClientInfo($request->fd)['remote_ip'], 0);
        return true;
    }

    public static function onMessage(Server $server, $request)
    {
        Logger::PrintLine('New Message,fd: ' . $request->fd . ', ip: ' . $server->getClientInfo($request->fd)['remote_ip'] . ', data: ' . $request->data, 0);
        return true;
    }

    public static function onClose(Server $server, $fd)
    {
        Logger::PrintLine('New Disconnection,fd: ' . $fd . ', ip: ' . $server->getClientInfo($fd)['remote_ip'], 0);
        return true;
    }
}