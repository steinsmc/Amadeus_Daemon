<?php


namespace Amadeus\Network;


use Amadeus\Network\Controller\register;

class Controller
{
    public static function onCall(int $fd, string $action, array $message): bool
    {
        switch ($action) {
            case 'register':
                return register::onCall($fd, $message);
                break;
            default:
                Reactor::rageQuit($fd, 'Unexpected Action');
                return false;
                break;
        }
    }
}