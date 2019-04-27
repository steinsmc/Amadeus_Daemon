<?php


namespace Amadeus\Environment;

use Amadeus\Config\Config;
use Amadeus\Environment\Quota\Disk;
use Amadeus\IO\Logger;
use Amadeus\Process;

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
        $quota_disk=Config::get('quota_disk');
        system('mount > /tmp/server'.$SID.'_amadeus_quota.tmp');
        $fd=fopen('/tmp/server'.$SID.'_amadeus_quota.tmp','r');
        $existed=false;
        while(!feof($fd)){
            $line=fgets($fd);
            if(strpos($line,$quota_disk)!=false or is_int(strpos($line,$quota_disk))){
                $existed=true;
                if(strpos($line,'usrquota,grpquota')==false){
                    fclose($fd);
                    Logger::printLine('Quota error: flags do not exist!',Logger::LOG_FATAL);
                    return false;
                }
            }
        }
        if($existed===false){
            Logger::printLine('Quota error: disk does not exist!',Logger::LOG_FATAL);
            fclose($fd);
            return false;
        }
        Logger::printLine('all is well');
        fclose($fd);
        Logger::printLine('quota info: '.'server'.$SID.' '.intval($Disk*512).' '.intval($Disk*1024).' '.intval(($Disk/$rate)/2).' '.intval($Disk/$rate).' '.Config::get('quota_disk'),Logger::LOG_INFORM);
        Disk::set('server'.$SID,intval($Disk*512),intval($Disk*1024),intval(($Disk/$rate)/2),intval($Disk/$rate),$quota_disk);
    }
    public function __destruct(){

    }
}