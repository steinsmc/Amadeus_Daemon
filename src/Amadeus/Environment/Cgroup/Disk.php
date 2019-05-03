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
     * @param int $primary_id
     * @param int $secondary_id
     * @param int $bps_device
     * @param int $PID
     * @return bool
     */
    public static function set(string $c_blkio, int $primary_id, int $secondary_id, int $bps_device, int $PID): bool
    {
        is_dir($c_blkio) ?: mkdir($c_blkio);
        $fd = fopen($c_blkio . '/blkio.throttle.read_bps_device', 'w+');
        fwrite($fd, $primary_id . ':' . $secondary_id . ' ' . $bps_device);
        fclose($fd);
        if (trim(file_get_contents($c_blkio . '/blkio.throttle.read_bps_device')) != $primary_id . ':' . $secondary_id . ' ' . $bps_device) {
            return false;
        }
        $fd = fopen($c_blkio . '/blkio.throttle.write_bps_device', 'w+');
        fwrite($fd, $primary_id . ':' . $secondary_id . ' ' . $bps_device);
        fclose($fd);
        if (trim(file_get_contents($c_blkio . '/blkio.throttle.write_bps_device')) != $primary_id . ':' . $secondary_id . ' ' . $bps_device) {
            return false;
        }
        $fd = fopen($c_blkio . '/tasks', 'a');
        fwrite($fd, $PID);
        fclose($fd);
        if (strstr(trim(file_get_contents($c_blkio . '/tasks')), (string)$PID) === false) {
            return false;
        }
        return true;
    }

    /**
     * @param string $c_blkio
     * @return bool
     */
    public static function clear(string $c_blkio): bool
    {
        rmdir($c_blkio);
        return is_dir($c_blkio) ? false : true;
    }
}