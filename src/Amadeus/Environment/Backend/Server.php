<?php


namespace Amadeus\Environment\Backend;


use Amadeus\Config\Config;
use Amadeus\Environment\{Cgroup,Quota};
use Amadeus\IO\Logger;
use Amadeus\Plugin\Game\GameController;
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
        $group,
        $PID;
    /**
     * @var Cgroup
     */
    private $Cgroup;
    /**
     * @var Quota
     */
    private $Quota;
    /**
     * @var
     */
    private $gameTypeController;

    /**
     * Server constructor.
     * @param $SID
     * @param $key
     * @param $directory
     * @param $gameType
     * @param $cpu
     * @param $mem
     * @param $disk
     * @param $diskSpeed
     * @param $networkSpeed
     * @param $status
     */
    public function __construct($SID, $key, $directory, $gameType, $cpu, $mem, $disk, $diskSpeed, $networkSpeed, $status)
    {
        Logger::printLine('Server ' . $SID . ' is starting', Logger::LOG_INFORM);
        $this->SID = $SID;
        $this->key = $key;
        $this->directory = $directory;
        $this->gameType = $gameType;
        $this->cpu = $cpu;
        $this->mem = $mem;
        $this->disk = $disk;
        $this->diskSpeed = $diskSpeed;
        $this->networkSpeed = $networkSpeed;
        $this->status = $status;
        $this->user = 'server' . $SID;
        $this->group = 'server' . $SID;
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        if (!@is_dir($this->directory)) {
            Logger::printLine('Server' . $this->SID . ' directory does not exist ' . $this->directory, Logger::LOG_FATAL);
        }
        if(Config::get('quota_enabled')){
            $this->Quota = new Quota($this->SID, $this->disk);
        }
        if (Process::getGameControl()->getGameType($this->gameType) !== false) {
            $this->GameTypeController = Process::getGameControl()->getGameType($this->gameType);
            $this->GameTypeController->initServer($this->SID);
        } else {
            Logger::printLine('Server ' . $this->SID . ' failed to load ' . $this->gameType, Logger::LOG_FATAL);
            return false;
        }
        $this->PID = $this->GameTypeController->onServerStart($this->SID);
        Logger::printLine('server' . $this->SID . ' pid: ' . $this->PID);
        if(Config::get('cgroup_enabled')){
            $this->Cgroup = new Cgroup($this->SID, $this->cpu, $this->mem, $this->diskSpeed, $this->networkSpeed, $this->PID);
        }
        Logger::printLine('Server ' . $this->SID . ' successfully started', Logger::LOG_INFORM);
        return true;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function getLog(string $key)
    {
        if ($this->key === $key) {
            return $this->GameTypeController->onClientGetLog($this->SID);
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getDirectory():string
    {
        return $this->directory;
    }

    /**
     *
     */
    public function __destruct()
    {
        Logger::printLine('Deleting server' . $this->SID, Logger::LOG_INFORM);
        if ($this->GameTypeController instanceof GameController) {
            $this->GameTypeController->onServerStop($this->SID);
            $this->GameTypeController->finServer($this->SID);
        }
        unset($this->Cgroup);
        unset($this->Quota);
        return true;
    }
}