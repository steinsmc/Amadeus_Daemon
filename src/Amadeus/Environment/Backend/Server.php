<?php


namespace Amadeus\Environment\Backend;


use Amadeus\Environment\Cgroup;
use Amadeus\Environment\Quota;
use Amadeus\IO\Logger;

class Server
{
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
    private $Cgroup;
    private $Quota;

    public function __construct($SID, $Key, $Directory,$GameType, $Cpu, $Mem, $Disk, $DiskSpeed, $NetworkSpeed, $Status)
    {
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
        $this->Cgroup = new Cgroup($SID,$Cpu,$Mem,$DiskSpeed,$NetworkSpeed);
        $this->Quota = new Quota();
        Logger::printLine('Server ' . $SID . ' is starting', Logger::LOG_INFORM);
    }
}