<?php

namespace Amadeus;

use Amadeus\IO\Logger;
use Amadeus\Config\Config;

class Process
{
    public static function init()
    {
        Logger::register();
        Config::register();
        Logger::printLine('Amadeus System Successfully Started', 233);
    }
}