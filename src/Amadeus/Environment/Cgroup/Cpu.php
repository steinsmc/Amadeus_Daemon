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
     * @param int $rt_period_us
     * @param int $rt_runtime_us
     * @return bool
     */
    public static function set(string $c_cpu, int $rt_period_us, int $rt_runtime_us): bool
    {
        $fd = fopen($c_cpu . '/cpu.rt_period_us', 'w+');
        fwrite($fd, $rt_period_us);
        fclose($fd);
        if (trim(file_get_contents($c_cpu . '/cpu.rt_period_us')) != $rt_period_us) {
            return false;
        }
        $fd = fopen($c_cpu . '/cpu.rt_runtime_us', 'w+');
        fwrite($fd, $rt_runtime_us);
        fclose($fd);
        if (trim(file_get_contents($c_cpu . '/cpu.rt_runtime_us')) != $rt_runtime_us) {
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
        rmdir($c_cpu);
        return is_dir($c_cpu) ? false : true;
    }
}