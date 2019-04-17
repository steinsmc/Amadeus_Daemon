<?php


namespace Amadeus\Database\MySQL;


class StateMents
{
    private static $statements = array(
        'newServer' => '
            INSERT INTO `Servers`
			(`Key`, `Directory`, `Cpu`, `Mem`, `Disk`, `DiskSpeed`, `NetworkSpeed`) 
			VALUES
			(?,?,?,?,?,?,?)',
        'checkServerStatusBySID' => '
            SELECT * FROM `Servers` WHERE `SID`=?
        '
    );

    public static function getStatement($name)
    {
        if (array_key_exists($name, self::$statements)) {
            return self::$statements[$name];
        }else{
            return '';

        }
    }
}