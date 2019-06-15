<?php


namespace Amadeus\Runtime;

class Runtime
{
    private static $runtime;

    public static function register($runtime)
    {
        self::$runtime = $runtime;
    }

    public static function registerProcessID($name, $pid): bool
    {
        file_put_contents(self::$runtime . '/' . $name . '.pid', $pid);
        return file_exists(self::$runtime . '/' . $name . '.pid');
    }

    public static function deleteProcessID($name): bool
    {
        if(file_exists(self::$runtime . '/' . $name . '.pid')){
            unlink(self::$runtime . '/' . $name . '.pid');
        }
        return !file_exists(self::$runtime . '/' . $name . '.pid');
    }

    public static function getProcessCount(): int
    {
        return count(array_diff(scandir(self::$runtime), ['.', '..']));
    }

    public static function processExists($name): bool
    {
        return file_exists(self::$runtime . '/' . $name . '.pid');
    }
}