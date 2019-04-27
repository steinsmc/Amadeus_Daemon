<?php


namespace Amadeus\Database\MySQL;


/**
 * Class StateMents
 * @package Amadeus\Database\MySQL
 */
class StateMents
{
    /**
     * @var array
     */
    private static $statements = array(
        'newServer' => '
            INSERT INTO `Servers`
			(`Key`, `Directory`, `GameControl`, `Cpu`, `Mem`, `Disk`, `DiskSpeed`, `NetworkSpeed`, `Status`) 
			VALUES
			(?,?,?,?,?,?,?,?,?)',
        'checkServerStatusBySID' => '
            SELECT * FROM `Servers` WHERE `SID`=?
        ',
        'getServers' => '
        SELECT * FROM `Servers`
        ',
        'countServers'=>'
        SELECT COUNT(`SID`) AS `Numbers` FROM `Servers`
        ',
        'delServerBySID'=>'
        DELETE FROM `Servers` WHERE SID = ?
        '
    );

    /**
     * @param $name
     * @return mixed|string
     */
    public static function getStatement($name)
    {
        if (array_key_exists($name, self::$statements)) {
            return self::$statements[$name];
        } else {
            return '';
        }
    }
}