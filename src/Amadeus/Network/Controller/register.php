<?php


namespace Amadeus\Network\Controller;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use Amadeus\Network\Reactor;

/**
 * Class register
 * @package Amadeus\Network\Controller
 */
class register
{
    /**
     * @param int $fd
     * @param array $message
     * @return bool
     */
    public static function OnCall(int $fd, array $message): bool
    {
        if($message['password']!=Config::get('daemon_password')){
            Reactor::rageQuit($fd,'Invalid Password');
            return false;
        }
        if(Reactor::getUser($fd)->getStatus()==1){
            Reactor::rageQuit($fd,'Jeez');
            return false;
        }
        switch($message['type']){
            case 'rdms':
                Reactor::getUser($fd)->setStatus(1);
                Reactor::getUser($fd)->setType('rdms');
                break;
            default:
                Reactor::getUser($fd)->setStatus(1);
                Reactor::getUser($fd)->setType('guest');
                break;
        }
        Reactor::sendMessage($fd,'successfulRegistration',array('type'=>Reactor::getUser($fd)->getType(),'fd'=>Reactor::getUser($fd)->getFd(),'ip'=>Reactor::getUser($fd)->getIp()));
        return true;
    }

    public static function meetRequirement(array $message):bool{
        if(!empty($message['password']) && !empty($message['type'])){
            return true;
        }
        return false;
    }
}