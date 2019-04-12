<?php


namespace Amadeus\Network;

use Amadeus\Network\Frontend\User;
use Amadeus\Network\Verification\API;
use Swoole\WebSocket\Server as Server;
use Amadeus\IO\Logger;

class Reactor
{
    private static $userList = array();

    public static function onOpen(Server $server, $request)
    {
        self::$userList[$request->fd] = new User($request->fd,$server->getClientInfo($request->fd)['remote_ip']);
        Logger::PrintLine('New Connection,fd: ' . $request->fd . ', ip: ' . $server->getClientInfo($request->fd)['remote_ip'], 0);
        return true;
    }

    public static function onMessage(Server $server, $request)
    {
        if(self::$userList[$request->fd]->getIp() !== $server->getClientInfo($request->fd)['remote_ip']){
            self::rageQuit($request->fd,'IP change detected');
        }
        if(!API::isOkay($request)){
            self::rageQuit($request->fd,'Bad client');
        }
        Logger::PrintLine('New Message,fd: ' . $request->fd . ', ip: ' . $server->getClientInfo($request->fd)['remote_ip'] . ', data: ' . $request->data, 0);
        return true;
    }

    public static function onClose(Server $server, $fd)
    {
        unset(self::$userList[$fd]);
        Logger::PrintLine('New Disconnection,fd: ' . $fd . ', ip: ' . $server->getClientInfo($fd)['remote_ip'], 0);
        return true;
    }
    public static function rageQuit($fd, $reason = 'Undefined')
    {
        Logger::PrintLine('Kicked a user,fd: ' . $fd . ', Reason: ' . $reason, 1);
        WebSocketServer::getServer()->disconnect($fd, 4000, json_encode(array('action' => 'rageQuit', 'message' => $reason)));
        return true;
    }
}