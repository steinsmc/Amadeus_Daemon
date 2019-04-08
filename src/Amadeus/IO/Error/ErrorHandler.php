<?php


namespace Amadeus\IO\Error;


use Amadeus\IO\Logger;

class ErrorHandler
{
    public static function onError($errno, $errstr, $errfile, $errline){
        if (!(error_reporting() & $errno)) {
            return false;
        }
        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                Logger::printLine('PHP_ERROR ['.$errno.'] '.$errstr.' On line '.$errline.' In file '.$errfile.' Using '.PHP_VERSION.' On '.PHP_OS,6);
                break;
            CASE E_WARNING:
            case E_USER_WARNING:
                Logger::printLine('PHP_Warning ['.$errno.'] '.$errstr.' On line '.$errline.' In file '.$errfile.' Using '.PHP_VERSION.' On '.PHP_OS,4);
                break;
            case E_NOTICE:
            case E_USER_NOTICE:
                Logger::printLine('PHP_Notice ['.$errno.'] '.$errstr.' On line '.$errline.' In file '.$errfile.' Using '.PHP_VERSION.' On '.PHP_OS,1);
                break;
            default:
                Logger::printLine('PHP_Unknown ['.$errno.'] '.$errstr.' On line '.$errline.' In file '.$errfile.' Using '.PHP_VERSION.' On '.PHP_OS,3);
                break;
        }
        return true;
    }
}