<?php


namespace Amadeus\IO\Error;


use Amadeus\IO\Logger;

/**
 * Class ErrorHandler
 * @package Amadeus\IO\Error
 */
class ErrorHandler
{
    /**
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return bool
     */
    public static function onError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        if (!(error_reporting() & $errno)) {
            return false;
        }
        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                Logger::printLine('PHP_ERROR [' . $errno . '] ' . $errstr . ' On line ' . $errline . ' In file ' . $errfile . ' Using ' . PHP_VERSION . ' On ' . PHP_OS, Logger::LOG_FATAL);
                break;
            CASE E_WARNING:
            case E_USER_WARNING:
                Logger::printLine('PHP_Warning [' . $errno . '] ' . $errstr . ' On line ' . $errline . ' In file ' . $errfile . ' Using ' . PHP_VERSION . ' On ' . PHP_OS, Logger::LOG_PANIC);
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                Logger::printLine('PHP_Notice [' . $errno . '] ' . $errstr . ' On line ' . $errline . ' In file ' . $errfile . ' Using ' . PHP_VERSION . ' On ' . PHP_OS, Logger::LOG_WARNING);
                break;
            default:
                Logger::printLine('PHP_Unknown [' . $errno . '] ' . $errstr . ' On line ' . $errline . ' In file ' . $errfile . ' Using ' . PHP_VERSION . ' On ' . PHP_OS, Logger::LOG_DANGER);
                break;
        }
        return true;
    }
}