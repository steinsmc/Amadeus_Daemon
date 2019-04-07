<?php

namespace Amadeus;

class Logger
{

    public static function register()
    {
        self::printLine('|                   Amadeus                   |');
        //self::PrintLine('|                                        ' . \Service\Config\Config::Param('API_VERSION') . '|');
        self::printLine('Successfully registered', 233);
    }

    public static function printLine($Message, $Level = 0)
    {
            file_put_contents('Amadeus.log',"[" . date("H:i:s") . " " . self::GetLevel($Level) . "] " .debug_backtrace()[1]['class'].debug_backtrace()[1]['type'].debug_backtrace()[1]['function'].": ". $Message . PHP_EOL,FILE_APPEND);
            if (self::getLevel($Level) == "FATAL") {
                //exit("THE DAEMON DIES BECAUSE AN FATAL ERROR OCCURRED".PHP_EOL);
                exit;
            }
    }

    private static function getLevel($Level)
    {
        switch ($Level) {
            case 0:
                $stype = " INFORM";
                break;
            case 1:
                $stype = "WARNING";
                break;
            case 2:
                $stype = "  ERROR";
                break;
            case 3:
                $stype = " DANGER";
                break;
            case 4:
                $stype = "  PANIC";
                break;
            case 5:
                $stype = " DEADLY";
                break;
            case 6:
                $stype = "  FATAL";
                break;
            case 233:
                $stype = "SUCCESS";
                break;
            default:
                $stype = " INFORM";
        }
        return $stype;
    }
}

?>