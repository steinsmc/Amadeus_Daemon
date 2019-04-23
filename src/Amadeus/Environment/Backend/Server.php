<?php


namespace Amadeus\Environment\Backend;


use Amadeus\Environment\Cgroup;
use Amadeus\Environment\Quota;
use Amadeus\IO\Logger;
use Amadeus\Process;

/**
 * Class Server
 * @package Amadeus\Environment\Backend
 */
class Server
{
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var string
     */
    /**
     * @var string
     */
    private
        $SID,
        $key,
        $directory,
        $gameType,
        $cpu,
        $mem,
        $disk,
        $diskSpeed,
        $networkSpeed,
        $status,
        $user,
        $group;
    /**
     * @var Cgroup
     */
    private $Cgroup;
    /**
     * @var Quota
     */
    private $Quota;
    private $GameTypeController;

    /**
     * Server constructor.
     * @param $SID
     * @param $Key
     * @param $Directory
     * @param $GameType
     * @param $Cpu
     * @param $Mem
     * @param $Disk
     * @param $DiskSpeed
     * @param $NetworkSpeed
     * @param $Status
     */
    public function __construct($SID, $Key, $Directory, $GameType, $Cpu, $Mem, $Disk, $DiskSpeed, $NetworkSpeed, $Status)
    {
        Logger::printLine('Server ' . $SID . ' is starting', Logger::LOG_INFORM);
        $this->SID = $SID;
        $this->key = $Key;
        $this->directory = $Directory;
        $this->gameType = $GameType;
        $this->cpu = $Cpu;
        $this->mem = $Mem;
        $this->disk = $Disk;
        $this->diskSpeed = $DiskSpeed;
        $this->networkSpeed = $NetworkSpeed;
        $this->status = $Status;
        $this->user = 'server' . $SID;
        $this->group = 'server' . $SID;
        $this->Cgroup = new Cgroup($SID,$Cpu,$Mem,$DiskSpeed,$NetworkSpeed,233);
        $this->Quota = new Quota();
        if(!Process::getGameType()->getGameType($this->gameType)){
            $this->GameTypeController = Process::getGameType()->getGameType($this->gameType);
        }else{
            Logger::printLine('Server ' . $SID . ' failed to load '.$this->gameType, Logger::LOG_FATAL);
        }
        Logger::printLine('Server ' . $SID . ' successfully started', Logger::LOG_INFORM);
    }
}