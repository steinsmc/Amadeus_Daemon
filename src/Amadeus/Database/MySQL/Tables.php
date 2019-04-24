<?php


namespace Amadeus\Database\MySQL;


/**
 * Class Tables
 * @package Amadeus\Database\MySQL
 */
class Tables
{
    /**
     * @var array
     */
    private static $tables = array(
        "Servers" => "
			CREATE TABLE IF NOT EXISTS `Servers`(
				`SID` INT UNSIGNED AUTO_INCREMENT,
				`Key` VARCHAR(36) NOT NULL,
				`Directory` VARCHAR(1000) NOT NULL,
				`GameControl` VARCHAR(20) NOT NULL,
				`Cpu` TINYINT UNSIGNED NOT NULL,
				`Mem` MEDIUMINT UNSIGNED NOT NULL,
				`Disk` MEDIUMINT UNSIGNED NOT NULL,
				`DiskSpeed` SMALLINT UNSIGNED NOT NULL,
				`NetworkSpeed` SMALLINT UNSIGNED NOT NULL,
				`Status` BOOLEAN NOT NULL,
				PRIMARY KEY ( `SID` )
			)ENGINE=InnoDB DEFAULT CHARSET=utf8;"
    );

    /**
     * @return array
     */
    public static function getTables(): array
    {
        return self::$tables;
    }
}