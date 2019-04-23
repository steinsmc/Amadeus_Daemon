<?php


namespace Amadeus\Environment;

use Amadeus\Config\Config;
use Amadeus\Environment\Cgroup\Cpu;
use Amadeus\Environment\Cgroup\Disk;
use Amadeus\Environment\Cgroup\Mem;
use Amadeus\Environment\Cgroup\Network;
use Amadeus\IO\Logger;

/**
 * Class Cgroup
 * @package Amadeus\Environment
 */
class Cgroup
{
    /**
     * @var mixed|string
     */
    private $cgroupBase;
    /**
     * @var int
     */
    /**
     * @var int
     */
    /**
     * @var int
     */
    /**
     * @var int
     */
    /**
     * @var int
     */
    /**
     * @var int
     */
    private
        $SID,
        $Cpu,
        $Mem,
        $DiskSpeed,
        $NetworkSpeed,
        $PID;
    /**
     * @var string
     */
    /**
     * @var string
     */
    /**
     * @var string
     */
    /**
     * @var string
     */
    private
        $c_cpu,
        $c_memory,
        $c_blkio,
        $c_net_cls;

    /**
     * Cgroup constructor.
     * @param int $SID
     * @param int $Cpu
     * @param int $Mem
     * @param int $DiskSpeed
     * @param int $NetworkSpeed
     * @param int $PID
     */
    public function __construct(int $SID, int $Cpu, int $Mem, int $DiskSpeed, int $NetworkSpeed, int $PID)
    {
        $this->cgroupBase = Config::get('cgroup_dir');
        $this->c_cpu = $this->cgroupBase . 'cpu' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/';
        $this->c_memory = $this->cgroupBase . 'memory' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/';
        $this->c_blkio = $this->cgroupBase . 'blkio' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/';
        $this->c_net_cls = $this->cgroupBase . 'net_cls' . DIRECTORY_SEPARATOR . 'server' . $this->SID . '/';
        $this->SID = $SID;
        $this->Cpu = $Cpu;
        $this->Mem = $Mem;
        $this->DiskSpeed = $DiskSpeed;
        $this->NetworkSpeed = $NetworkSpeed;
        $this->PID = $PID;
        $this->cgroupInit();
    }

    /**
     *
     */
    private function cgroupInit()
    {
        is_dir($this->c_cpu) ? Logger::printLine('cpu limit for ' . $this->SID . ' exists', Logger::LOG_INFORM) : mkdir($this->c_cpu);
        is_dir($this->c_memory) ? Logger::printLine('memory limit for ' . $this->SID . ' exists', Logger::LOG_INFORM) : mkdir($this->c_memory);
        is_dir($this->c_blkio) ? Logger::printLine('disk speed limit for ' . $this->SID . ' exists', Logger::LOG_INFORM) : mkdir($this->c_blkio);
        is_dir($this->c_net_cls) ? Logger::printLine('network speed limit for ' . $this->SID . ' exists', Logger::LOG_INFORM) : mkdir($this->c_net_cls);
        Cpu::set($this->c_cpu,$this->Cpu);
        Mem::set($this->c_memory,$this->Mem);
        Disk::set($this->c_blkio,$this->DiskSpeed);
        Network::set($this->c_net_cls,$this->NetworkSpeed);
    }
}