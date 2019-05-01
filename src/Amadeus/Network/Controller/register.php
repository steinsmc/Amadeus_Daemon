<?php


namespace Amadeus\Network\Controller;


use Amadeus\IO\Logger;

class register
{
    public static function OnCall(int $fd, array $message): bool
    {
        return true;
    }
}