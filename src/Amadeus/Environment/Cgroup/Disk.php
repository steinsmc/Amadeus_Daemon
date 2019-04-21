<?php


namespace Amadeus\Environment\Cgroup;


/**
 * Class Disk
 * @package Amadeus\Environment\Cgroup
 */
class Disk
{
    /**
     * @param string $c_blkio
     * @param int $diskSpeed
     * @return bool
     */
    public static function set(string $c_blkio, int $diskSpeed):bool{
        return true;
    }
}