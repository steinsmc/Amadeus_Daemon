<?php


namespace Amadeus;


class Process
{
    public static function init()
    {
        Logger::register();
        Logger::printLine('Amadeus System Successfully Started',233);
    }
}