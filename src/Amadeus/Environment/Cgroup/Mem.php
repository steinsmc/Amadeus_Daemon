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
     * @param int $limit_in_bytes
     * @return bool
     */
    public static function set(string $c_memory, int $limit_in_bytes): bool
    {
        $fd = fopen($c_memory . '/memory.oom_control', 'w+');
        fwrite($fd, 1);
        fclose($fd);
        if (strstr(trim(file_get_contents($c_memory . '/memory.oom_control')), '1') === false) {
            return false;
        }
        $fd = fopen($c_memory . '/memory.limit_in_bytes', 'w+');
        fwrite($fd, $limit_in_bytes);
        fclose($fd);
        if (trim(file_get_contents($c_memory . '/memory.limit_in_bytes')) != $limit_in_bytes) {
            return false;
        }
        return true;
    }

    /**
     * @param string $c_memory
     * @return bool
     */
    public static function clear(string $c_memory): bool
    {
        rmdir($c_memory);
        return is_dir($c_memory)?false:true;
    }
}