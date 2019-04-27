<?php


namespace Amadeus\Config\Sample;


use Amadeus\IO\Logger;

/**
 * Class SampleConfig
 * @package Amadeus\Config\Sample
 */
class SampleConfig
{
    /**
     * @var array
     */
    private static $config = array(
        'daemon_mysql_host' => '127.0.0.1',
        'daemon_mysql_port' => 3306,
        'daemon_mysql_dbname' => 'amadeus_daemon',
        'daemon_mysql_user' => 'amadeus_daemon',
        'daemon_mysql_password' => 'amadeus',
        'daemon_mysql_sock' => "/tmp/mysql.sock",
        'daemon_address' => '127.0.0.1',
        'daemon_port' => '2333',
        'daemon_workers' => '4',
        'daemon_password' => 'lixisgay',
        'cgroup_dir'=>'/sys/fs/cgroup',
        'cgroup_disk_primary_id'=>253,
        'cgroup_disk_secondary_id'=>0,
        'quota_disk'=>'/dev/vda1',
        'quota_file_to_size_rate'=>4
    );

    /**
     * @return array
     */
    public static function generate():array
    {
        return self::$config;
    }

    /**
     * @param array $config
     * @return bool
     */
    public static function verify(array $config):bool
    {
        Logger::printLine($config, Logger::LOG_INFORM);
        foreach (self::$config as $k => $v) {
            Logger::printLine($k, Logger::LOG_INFORM);
            if (!array_key_exists($k, $config)) {
                Logger::printLine('invalid',Logger::LOG_PANIC);
                return false;
            }
            Logger::printLine('valid',Logger::LOG_INFORM);
        }
        Logger::printLine('success',Logger::LOG_SUCCESS);
        return true;
    }
}