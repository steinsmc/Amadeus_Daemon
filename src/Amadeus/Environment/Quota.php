<?php


namespace Amadeus\Environment;

use Amadeus\Config\Config;
use Amadeus\Environment\Quota\Disk;
use Amadeus\IO\Logger;

/**
 * Class Quota
 * @package Amadeus\Environment
 */
class Quota
{
    /**
     * Quota constructor.
     * @param int $SID
     * @param int $Disk
     */
    public function __construct(int $SID, int $Disk)
    {
        $rate=Config::get('quota_file_to_size_rate');
        Logger::printLine('data: '.'server'.$SID.','.intval($Disk*512).','.intval($Disk*1024).','.intval(($Disk/$rate)/2).','.intval($Disk/$rate).','.Config::get('quota_disk'),Logger::LOG_INFORM);
        Disk::set('server'.$SID,intval($Disk*512),intval($Disk*1024),intval(($Disk/$rate)/2),intval($Disk/$rate),Config::get('quota_disk'));
    }
}