<?php


namespace Amadeus\Environment;

use Amadeus\Config\Config;
use Amadeus\Environment\Cgroup\Cpu;
use Amadeus\Environment\Cgroup\Disk;
use Amadeus\Environment\Cgroup\Mem;
use Amadeus\Environment\Cgroup\Network;
use Amadeus\IO\Logger;

class Cgroup
{
    private $cgroupBase;
    private
        $SID,
        $Cpu,
        $Mem,
        $Disk,
        $DiskSpeed,
        $NetworkSpeed;

    public function __construct(int $SID, int $Cpu, int $Mem, int $DiskSpeed, int $NetworkSpeed)
    {
        $this->cgroupBase = Config::get('cgroup_dir');
        $this->SID = $SID;
        $this->Cpu = $Cpu;
        $this->Mem = $Mem;
        $this->DiskSpeed = $DiskSpeed;
        $this->NetworkSpeed = $NetworkSpeed;
        $this->cgroupInit();
    }

    private function cgroupInit()
    {
        is_dir($this->cgroupBase . 'cpu' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/')?Logger::printLine('cpu limit for '.$this->SID.'exists',Logger::LOG_INFORM):mkdir($this->cgroupBase . 'cpu' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/');
        is_dir($this->cgroupBase . 'memory' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/')?Logger::printLine('memory limit for '.$this->SID.'exists',Logger::LOG_INFORM):mkdir($this->cgroupBase . 'memory' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/');
        is_dir($this->cgroupBase . 'blkio' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/')?Logger::printLine('diskspeed limit for '.$this->SID.'exists',Logger::LOG_INFORM):mkdir($this->cgroupBase . 'blkio' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/');
        is_dir($this->cgroupBase . 'net_cls' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/')?Logger::printLine('networkspeed limit for '.$this->SID.'exists',Logger::LOG_INFORM):mkdir($this->cgroupBase . 'net_cls' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/');
    }
}