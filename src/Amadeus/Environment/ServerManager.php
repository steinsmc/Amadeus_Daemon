<?php


namespace Amadeus\Environment;

use Amadeus\Environment\Backend\Server;
use Amadeus\IO\Logger;
use Amadeus\Process;

/**
 * Class ServerManager
 * @package Amadeus\Environment
 */
class ServerManager
{
    /**
     * @var
     */
    private $servers;

    /**
     * ServerManager constructor.
     */
    public function __construct()
    {
        Quota::sanityCheck() ? Logger::printLine('Quota sanity check succeed', Logger::LOG_SUCCESS) : Logger::printLine('Quota sanity check failed', Logger::LOG_FATAL);
        Cgroup::sanityCheck() ? Logger::printLine('Cgroup sanity check succeed', Logger::LOG_SUCCESS) : Logger::printLine('Cgroup sanity check failed', Logger::LOG_FATAL);
        Logger::PrintLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    /**
     * @return bool
     */
    public function startAllServers(): bool
    {
        Logger::printLine('Starting all server',Logger::LOG_INFORM);
        $servers = Process::getMySQL()->getServers();
        foreach ($servers as $SID => $server) {
            @mkdir($server['Directory'], 0755);
            @system('chown -R server' . $SID . ':server' . $SID . ' ' . $server['Directory']);
            $this->servers[$SID] = new Server($server['SID'], $server['Key'], $server['Directory'], $server['GameControl'], $server['Cpu'], $server['Mem'], $server['Disk'], $server['DiskSpeed'], $server['NetworkSpeed'], $server['Status']);
            $this->servers[$SID]->start();
        }
        return true;
    }

    /**
     * @param string $gameType
     * @param int $cpu
     * @param int $mem
     * @param int $disk
     * @param int $diskSpeed
     * @param int $networkSpeed
     * @return array
     */
    public function newServer(string $gameType = 'PM', int $cpu = 100, int $mem = 1024, int $disk = 5120, int $diskSpeed = 5, int $networkSpeed = 1): array
    {
        Logger::printLine('Creating a new server',Logger::LOG_INFORM);
        $SID = Process::getMySQL()->countServers() + 1;
        $directory = Process::getBase() . '/servers/server' . $SID;
        @system('useradd -m -d ' . $directory . ' server' . $SID . ' 2>&1');
        $key = Process::getMySQL()->newServer($directory, $gameType, $cpu, $mem, $disk, $diskSpeed, $networkSpeed);
        $this->servers[$SID] = new Server($SID, $key, $directory, $gameType, $cpu, $mem, $disk, $diskSpeed, $networkSpeed, true);
        $this->servers[$SID]->start();
        return array('SID' => $SID, 'Directory' => $directory);
    }

    /**
     * @param int $SID
     * @return bool
     */
    public function delServer(int $SID): bool
    {
        Logger::printLine('Deleting server'.$SID,Logger::LOG_WARNING);
        if (isset($this->servers[$SID])) {
            unset($this->servers[$SID]);
            $Directory = Process::getBase() . '/servers/server' . $SID;
            @system('userdel -f server >/dev/null 2>&1' . $SID);
            @system('rm -rf ' . $Directory);
            Process::getMySQL()->delServerBySID($SID);
            return true;
        } else {
            Logger::printLine('Failed to delete server: server does not exist', Logger::LOG_DANGER);
            return false;
        }
    }

    /**
     * @param int $SID
     * @return Server|null
     */
    public function getServer(int $SID): ?Server
    {
        return isset($this->servers[$SID]) ? $this->servers[$SID] : null;
    }
}