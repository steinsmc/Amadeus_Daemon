<?php


namespace Amadeus\Network;


use Amadeus\Network\Controller\rdms_command;
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
                if (($ret = register::meetRequirement($fd, $message)) == true) {
                    return register::onCall($fd, $message);
                }
                Reactor::rageQuit($fd, $ret);
                return false;
                break;
            case 'rdms_command':
                if (($ret = rdms_command::meetRequirement($fd, $message)) == true) {
                    return rdms_command::onCall($fd, $message);
                }
                Reactor::rageQuit($fd, $ret);
                return false;
                break;
            default:
                Reactor::rageQuit($fd, 'Unexpected Action');
                return false;
                break;
        }
    }
}