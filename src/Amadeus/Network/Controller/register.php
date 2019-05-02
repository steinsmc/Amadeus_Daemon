<?php


namespace Amadeus\Network\Controller;


use Amadeus\IO\Logger;

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
        return true;
    }
}