<?php


namespace Amadeus\Environment\Cgroup;


/**
 * Class Network
 * @package Amadeus\Environment\Cgroup
 * @Deprecated
 */
class Network
{
    /**
     * @param string $c_net_cls
     * @param int $networkSpeed
     * @param int $PID
     * @return bool
     */
    public static function set(string $c_net_cls, int $networkSpeed, int $PID): bool
    {
        is_dir($c_net_cls) ?: mkdir($c_net_cls);
        return true;
    }

    /**
     * @param string $c_net_cls
     * @return bool
     */
    public static function clear(string $c_net_cls): bool
    {
        @rmdir($c_net_cls);
        return is_dir($c_net_cls) ? false : true;
    }
}