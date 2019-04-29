<?php


namespace Amadeus\IO\Exception;


use Amadeus\IO\Logger;

/**
 * Class ExceptionHandler
 * @package Amadeus\IO\Exception
 */
class ExceptionHandler
{
    /**
     * @param $exception
     * @return bool
     */
    public static function onException($exception): bool
    {
        Logger::printLine("Exception: [" . $exception->getFile() . ":" . $exception->getLine() . "]" . $exception->getMessage() . PHP_EOL . $exception->getCode() . PHP_EOL . $exception->getTraceAsString(), Logger::LOG_FATAL);
        return true;
    }
}
