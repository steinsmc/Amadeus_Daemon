<?php


namespace Amadeus\Database\MySQL;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use mysqli;

;

class MySQL
{
    private static $MySQL;

    public static function register()
    {
        if (is_null(Config::get('daemon_mysql_sock'))) {
            Logger::printLine('Not using socket');
            self::$MySQL = new mysqli(Config::get('daemon_mysql_host'), Config::get('daemon_mysql_user'), Config::get('daemon_mysql_password'), Config::get('daemon_mysql_dbname'), Config::get('daemon_mysql_port'));
        } else {
            Logger::printLine('Using socket');
            self::$MySQL = new mysqli(Config::get('daemon_mysql_host'), Config::get('daemon_mysql_user'), Config::get('daemon_mysql_password'), Config::get('daemon_mysql_dbname'), Config::get('daemon_mysql_port'), Config::get('daemon_mysql_sock'));
        }
        self::$MySQL->set_charset("utf8");
        if (self::$MySQL->connect_error) {
            Logger::PrintLine('Failed to connect to MySQL server', 6);
            return false;
        }
        foreach(Tables::getTables() as $table){
            self::$MySQL->query($table);
        }
        Logger::printLine('Successfully registered', 233);
    }
}