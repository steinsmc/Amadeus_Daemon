<?php


namespace Amadeus\Database\MySQL;


class StateMents
{
    private static $statements = array(
        'newServer' => '
            INSERT INTO `Servers`
			(`Key`, `Directory`, `GameType`, `Cpu`, `Mem`, `Disk`, `DiskSpeed`, `NetworkSpeed`, `Status`) 
			VALUES
			(?,?,?,?,?,?,?,?,?)',
        'checkServerStatusBySID' => '
            SELECT * FROM `Servers` WHERE `SID`=?
        ',
        'getServers' => '
        SELECT * FROM `Servers`
        '
    );

    public static function getStatement($name)
    {
        if (array_key_exists($name, self::$statements)) {
            return self::$statements[$name];
        } else {
            return '';

        }
    }
}