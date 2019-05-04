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
     * @param int $cfs_period_us
     * @param int $cfs_quota_us
     * @param int $PID
     * @return bool
     */
    public static function set(string $c_cpu, int $cfs_period_us, int $cfs_quota_us, int $PID): bool
    {
        is_dir($c_cpu) ?: mkdir($c_cpu);
        $fd = fopen($c_cpu . '/cpu.cfs_period_us', 'w+');
        fwrite($fd, $cfs_period_us);
        fclose($fd);
        if (trim(file_get_contents($c_cpu . '/cpu.cfs_period_us')) != $cfs_period_us) {
            return false;
        }
        $fd = fopen($c_cpu . '/cpu.cfs_quota_us', 'w+');
        fwrite($fd, $cfs_quota_us);
        fclose($fd);
        if (trim(file_get_contents($c_cpu . '/cpu.cfs_quota_us')) != $cfs_quota_us) {
            return false;
        }
        $fd = fopen($c_cpu . '/tasks', 'a');
        fwrite($fd, $PID);
        fclose($fd);
        if (strstr(trim(file_get_contents($c_cpu . '/tasks')), (string)$PID) === false) {
            return false;
        }
        return true;
    }

    /**
     * @param string $c_cpu
     * @return bool
     */
    public static function clear(string $c_cpu): bool
    {
        @rmdir($c_cpu);
        return is_dir($c_cpu) ? false : true;
    }
}