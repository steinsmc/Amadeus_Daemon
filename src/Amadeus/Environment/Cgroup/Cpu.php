<?php


namespace Amadeus\Environment\Cgroup;


/**
 * Class Cpu
 * @package Amadeus\Environment\Cgroup
 */
class Cpu
{
    /**
     * @param string $c_cpu
     * @param int $cpu
     * @return bool
     */
    public static function set(string $c_cpu, int $cpu):bool{
        return true;
    }
}