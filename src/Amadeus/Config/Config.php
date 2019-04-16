<?php


namespace Amadeus\Config;

use Amadeus\IO\Logger;
use Amadeus\Config\Sample\SampleConfig;
use Amadeus\Process;

class Config
{
    private static $_CONFIG = array(
        'daemon_api_version' => 1,
        'daemon_os' => PHP_OS
    );

    public static function register()
    {
        if (!file_exists('Amadeus.conf')) {
            self::$_CONFIG = SampleConfig::generate();
            file_put_contents('Amadeus.conf', yaml_emit(self::$_CONFIG));
            Logger::printLine('Did not find Amadeus.conf, generating one');
        } else {
            self::$_CONFIG = yaml_parse_file('Amadeus.conf');
            if (!is_array(self::$_CONFIG)) {
                Logger::printLine('Failed to load Amadeus.conf', 6);
            }
            if (!SampleConfig::verify(self::$_CONFIG)) {
                Logger::printLine('Failed to read Amadeus.conf', 6);
            }
        }
        self::$_CONFIG['daemon_api_version'] = 1;
        self::$_CONFIG['daemon_os'] = PHP_OS;
        Logger::printLine('Successfully registered', 233);
    }

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