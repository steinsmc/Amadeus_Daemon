<?php


namespace Amadeus\Environment\Cgroup;


/**
 * Class Mem
 * @package Amadeus\Environment\Cgroup
 */
class Mem
{
    /**
     * @param string $c_memory
     * @param int $mem
     * @return bool
     */
    public static function set(string $c_memory, int $mem):bool{
        return true;
    }
}