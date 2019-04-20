<?php


namespace Amadeus\Environment\Cgroup;


class Disk
{
    public static function set(string $c_blkio, int $diskSpeed):bool{
        return true;
    }
}