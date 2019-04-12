<?php

namespace Amadeus\IO;

use Amadeus\Config\Config;

class Logger
{

    public static function register()
    {
        self::printLine('|                   Amadeus                   |');
        self::PrintLine('|                                         v' . Config::get('daemon_api_version') . '  |');
        set_error_handler(['Amadeus\IO\Error\ErrorHandler', 'onError']);
        self::printLine('Successfully registered', 233);
    }

    public static function printLine($Message, $Level = 0)
    {
        file_put_contents('Amadeus.log', "[" . date("H:i:s") . " " . self::GetLevel($Level) . "] " . @debug_backtrace()[1]['class'] . @debug_backtrace()[1]['type'] . @debug_backtrace()[1]['function'] . ": " . $Message . PHP_EOL, FILE_APPEND);
        if (self::getLevel($Level) == "  FATAL") {
            //exit("THE DAEMON DIES BECAUSE AN FATAL ERROR OCCURRED".PHP_EOL);
            self::printLine("THE DAEMON DIES BECAUSE AN FATAL ERROR OCCURRED", 7);
            @unlink('Amadeus.pid');
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
            case 7:
                $stype = "   DEAD";
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