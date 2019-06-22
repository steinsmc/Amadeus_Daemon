<?php


namespace Amadeus\Network\Controller;


use Amadeus\Network\Reactor;

class rdms_command
{
    public static function OnCall(int $fd, array $message): bool
    {
        Reactor::sendMessage($fd,'我的天啊',array('message'=>'好啦好啦我收到了不过还没继续写呢'));
        return false;
    }

    public static function meetRequirement(int $fd, array $message)
    {
        if(Reactor::getUser($fd)->getStatus()==false || Reactor::getUser($fd)->getType()!=4){
            return 'You are prohibited from using the rdms command';
        }
        return true;
    }
}