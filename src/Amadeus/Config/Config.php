<?php


namespace Amadeus\Config;

use Amadeus\IO\Logger;
use Amadeus\Config\Sample\SampleConfig;
use Amadeus\Process;

/**
 * Class Config
 * @package Amadeus\Config
 */
class Config
{
    /**
     * @var array
     */
    private static $_CONFIG = array(
        'daemon_api_version' => 1,
        'daemon_os' => PHP_OS,
        'daemon_cpu_cores' => 1
    );

    /**
     * @return bool
     */
    public static function register(): bool
    {
        if (!file_exists('Amadeus.conf')) {
            self::$_CONFIG = SampleConfig::generate();
            file_put_contents('Amadeus.conf', yaml_emit(self::$_CONFIG));
            Logger::printLine('Did not find Amadeus.conf, generating one');
        } else {
            self::$_CONFIG = yaml_parse_file('Amadeus.conf');
            if (!is_array(self::$_CONFIG)) {
                Logger::printLine('Failed to load Amadeus.conf', Logger::LOG_FATAL);
                return false;
            }
            if (!SampleConfig::verify(self::$_CONFIG)) {
                Logger::printLine('Failed to read Amadeus.conf', Logger::LOG_FATAL);
                return false;
            }
        }
        self::$_CONFIG['daemon_api_version'] = 1;
        self::$_CONFIG['daemon_os'] = PHP_OS;
        exec('cat /proc/cpuinfo | grep "processor"| wc -l',$ret);
        self::$_CONFIG['daemon_cpu_cores'] = intval(trim($ret[0]));
        Logger::printLine('Successfully registered', Logger::LOG_SUCCESS);
        return true;
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function get($key)
    {
        if (isset(self::$_CONFIG[$key])) {
            return self::$_CONFIG[$key];
        } else {
            if (isset(SampleConfig::generate()[$key])) {
                return SampleConfig::generate()[$key];
            }
            return '';
        }
    }
}