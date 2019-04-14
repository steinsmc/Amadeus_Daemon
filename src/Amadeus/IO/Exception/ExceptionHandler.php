<?php


namespace Amadeus\IO\Exception;


use Amadeus\IO\Logger;
use Exception;

class ExceptionHandler
{
    public static function onException(Exception $exception)
    {
        Logger::printLine("Exception: [".$exception->getFile().":".$exception->getLine()."]".$exception->getMessage()." \n <pre>".$exception->getCode()."</pre> \n ".$exception->getTraceAsString(),6);
        return true;
    }
}
