<?php


namespace Amadeus\Environment\Cgroup;


/**
 * Class Network
 * @package Amadeus\Environment\Cgroup
 */
class Network
{
    /**
     * @param string $c_net_cls
     * @param int $networkSpeed
     * @return bool
     */
    public static function set(string $c_net_cls, int $networkSpeed):bool{
        return true;
    }
}