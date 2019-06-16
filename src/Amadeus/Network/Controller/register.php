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
        if(Reactor::getUser($fd)->getStatus()==1){
            Reactor::rageQuit($fd,'Jeez');
            return false;
        }
        switch($message['type']){
            case 'rdms':
                if(Config::get('rdms_enabled')==false){
                    Reactor::rageQuit($fd,'rdms disabled');
                    return false;
                }
                Reactor::getUser($fd)->setType(4);
                if($message['password']!=Config::get('rdms_password')){
                    Reactor::rageQuit($fd,'Invalid Password');
                    return false;
                }
                Reactor::getUser($fd)->setStatus(1);
                Reactor::sendMessage($fd,'previousLogs',array('data'=>Logger::getLog()));
                Reactor::addRdms($fd);
                break;
            default:
                Reactor::getUser($fd)->setType(0);
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