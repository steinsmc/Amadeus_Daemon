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
     * @return bool
     */
    public static function set(string $c_blkio, int $primary_id, int $secondary_id, int $bps_device): bool
    {
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
        return true;
    }
}