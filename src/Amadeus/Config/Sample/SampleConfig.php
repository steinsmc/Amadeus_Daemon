<?php


namespace Amadeus\Config\Sample;


use Amadeus\IO\Logger;

class SampleConfig
{
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
        'daemon_password' => 'lixisgay'
    );

    public static function generate()
    {
        return self::$config;
    }

    public static function verify($config)
    {
        Logger::printLine($config);
        foreach (self::$config as $k => $v) {
            Logger::printLine($k);
            if (!array_key_exists($k, $config)) {
                return false;
            }
        }
        return true;
    }
}