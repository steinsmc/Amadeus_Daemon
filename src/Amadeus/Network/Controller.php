<?php


namespace Amadeus\Network;


use Amadeus\Network\Controller\register;

/**
 * Class Controller
 * @package Amadeus\Network
 */
class Controller
{
    /**
     * @param int $fd
     * @param string $action
     * @param array $message
     * @return bool
     */
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