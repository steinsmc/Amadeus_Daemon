<?php


namespace Amadeus\Network;

use Amadeus\Network\Frontend\User;
use Amadeus\Network\Verification\API;
use Swoole\WebSocket\Server;
use Swoole\Websocket\Frame;
use Amadeus\IO\Logger;
use Amadeus\Process;

/**
 * Class Reactor
 * @package Amadeus\Network
 */
class Reactor
{
    /**
     * @var array
     */
    private static $userList = array();
    private static $rdmsList = array();

    /**
     * @param Server $server
     * @param object $request
     * @return bool
     */
    public static function onOpen(Server $server, object $request): bool
    {
        self::$userList[$request->fd] = new User($request->fd, $server->getClientInfo($request->fd)['remote_ip']);
        Logger::PrintLine('New Connection,fd: ' . $request->fd . ', ip: ' . $server->getClientInfo($request->fd)['remote_ip'], Logger::LOG_INFORM);
        return true;
    }

    /**
     * @param Server $server
     * @param Frame $request
     * @return bool
     */
    public static function onMessage(Server $server, Frame $request): bool
    {
        Logger::PrintLine('New Message,fd: ' . $request->fd . ', ip: ' . $server->getClientInfo($request->fd)['remote_ip'] . ', data: ' . $request->data, Logger::LOG_INFORM);
        if (self::$userList[$request->fd]->getIp() !== $server->getClientInfo($request->fd)['remote_ip']) {
            self::rageQuit($request->fd, 'IP change detected');
        }
        if (($data = API::unpackData($request->data)) === null) {
            self::rageQuit($request->fd, 'Bad request');
            return false;
        }
        if (!API::isOkay($request)) {
            self::rageQuit($request->fd, 'Bad client');
            return false;
        }
        return Controller::onCall($request->fd, $data['action'], $data['message']);
    }

    /**
     * @param Server $server
     * @param int $fd
     * @return bool
     */
    public static function onClose(Server $server, int $fd): bool
    {
        unset(self::$userList[$fd]);
        if (!empty(self::$rdmsList[$fd])) {
            unset(self::$rdmsList[$fd]);
        }
        Logger::PrintLine('New Disconnection,fd: ' . $fd . ', ip: ' . $server->getClientInfo($fd)['remote_ip'], Logger::LOG_INFORM);
        return true;
    }

    /**
     * @param int $fd
     * @param string $reason
     * @return bool
     */
    public static function rageQuit(int $fd, string $reason = 'Undefined'): bool
    {
        Logger::PrintLine('Kicked a user,fd: ' . $fd . ', Reason: ' . $reason, Logger::LOG_WARNING);
        Process::getWebSocketServer()->getServer()->disconnect($fd, 4000, json_encode(array('action' => 'rageQuit', 'message' => array('reason' => $reason))));
        return true;
    }

    /**
     * @param int $fd
     * @param string $action
     * @param array $message
     * @return bool
     */
    public static function sendMessage(int $fd, string $action, array $message): bool
    {
        $message['time'] = date('Y-m-d H:i:s', time());
        Process::getWebSocketServer()->getServer()->push($fd, json_encode(array('action' => $action, 'message' => $message), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $detail=json_encode($message, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if(strlen($detail)>100){
            $detail='*';
        }
        Logger::printLine('Sending user' . $fd . ' a ' . $action . ' message, message detail: ' . $detail, Logger::LOG_INFORM);
        return true;
    }

    public static function sendRdms(string $action, array $message): bool
    {
        $message['time'] = date('Y-m-d H:i:s', time());
        foreach(self::$rdmsList as $item=>$none){
            Process::getWebSocketServer()->getServer()->push($item, json_encode(array('action' => $action, 'message' => $message), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        return true;
    }

    /**
     * @param int $fd
     * @return User|null
     */
    public static function getUser(int $fd): ?User
    {
        return isset(self::$userList[$fd]) ? self::$userList[$fd] : null;
    }

    public static function addRdms(int $fd): bool
    {
        self::$rdmsList[$fd] = 'lol';
        return true;
    }

    public static function getRdmsList(): array
    {
        return self::$rdmsList;
    }
}