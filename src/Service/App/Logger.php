<?php

namespace Service\App;

class Logger
{

    public static function register()
    {
        self::PrintLine('|                   Amadeus                   |');
        self::PrintLine('|                                      ' . \Service\namespace_var_get('_CONFIG')['API_VERSION'] . '|');
        Logger::PrintLine('\Service\App\Logger::Register成功', 233);
    }

    public static function PrintLine($Message, $Level = 0)
    {
        if (preg_match('/WIN*/', PHP_OS)) {
            echo "[" . date("H:i:s") . " " . self::GetLevel($Level) . "] " . $Message . "\r\n";
            //MySQL::AddLog("[" . date("H:i:s") . " " . self::GetLevel($Level) . "] " . $Message);
        } else {
            $c = "37";
            if ($Level > 0) {
                $c = "33";
            }
            if ($Level > 1) {
                $c = "31";
            }
            if ($Level > 4) {
                $c = '40;31';
            }
            if ($Level == 233) {
                $c = '40;32';
            }
            $Message = str_replace('"', '\"', $Message);
            if ($Level > 4) {
                if ($Level == 233) {
                    system('echo -e "\033[32m ------------------------------------------------- \033[0m"');
                } else {
                    system('echo -e "\033[31m ------------------------------------------------- \033[0m"');
                }
            }
            system('echo -e "\033[' . $c . 'm ' . "[" . date("H:i:s") . " " . self::GetLevel($Level) . "] " . $Message . ' \033[0m"');
            if ($Level > 4) {
                if ($Level == 233) {
                    system('echo -e "\033[32m ------------------------------------------------- \033[0m"');
                } else {
                    system('echo -e "\033[31m ------------------------------------------------- \033[0m"');
                }
            }
            //MySQL::AddLog("[" . date("H:i:s") . " " . self::GetLevel($Level) . "] " . $Message);
            unset($c);
            if (self::GetLevel($Level) == "FATAL") {
                exit("THE DAEMON DIES BECAUSE AN FATAL ERROR OCCURRED\r\n");
            }
        }
    }

    private static function GetLevel($Level)
    {
        switch ($Level) {
            case 0:
                $stype = "INFORM";
                break;
            case 1:
                $stype = "WARNING";
                break;
            case 2:
                $stype = "ERROR";
                break;
            case 3:
                $stype = "DANGER";
                break;
            case 4:
                $stype = "PANIC";
                break;
            case 5:
                $stype = "DEADLY";
                break;
            case 6:
                $stype = "FATAL";
                break;
            case 233:
                $stype = "SUCCESS";
                break;
            default:
                $stype = "INFORM";
        }
        return $stype;
    }
}

?>