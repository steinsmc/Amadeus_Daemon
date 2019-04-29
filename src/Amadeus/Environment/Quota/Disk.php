<?php


namespace Amadeus\Environment\Quota;

use Amadeus\IO\Logger;
use Exception;

/**
 * Class Disk
 * @package Amadeus\Environment\Quota
 */
class Disk
{
    /**
     * @param string $user
     * @param int $block_soft
     * @param int $block_hard
     * @param int $files_soft
     * @param int $files_hard
     * @param string $disk
     * @return bool
     */
    public static function set(string $user, int $block_soft, int $block_hard, int $files_soft, int $files_hard, string $disk):bool
    {
            system('setquota '.$user.' '.$block_soft.' '.$block_hard.' '.$files_soft.' '.$files_hard.' '.$disk.' 2>&1',$ret);
            if($ret!=0){
                Logger::printLine('Failed to setquota: command does not exist',Logger::LOG_FATAL);
                return false;
            }
            return true;
    }
}