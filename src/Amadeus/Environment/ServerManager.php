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
        $servers = Process::getMySQL()->getServers();
        foreach ($servers as $SID => $server) {
            //@system('chown -R server'.$SID.':server'.$SID.' '.$server['Directory']);
            $servers[$SID] = new Server($server['SID'], $server['Key'], $server['Directory'], $server['GameControl'], $server['Cpu'], $server['Mem'], $server['Disk'], $server['DiskSpeed'], $server['NetworkSpeed'], $server['Status']);
        }
        Logger::PrintLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    /**
     * @param string $GameType
     * @param int $Cpu
     * @param int $Mem
     * @param int $Disk
     * @param int $DiskSpeed
     * @param int $NetworkSpeed
     * @return array
     */
    public function newServer(string $GameType = 'PM', int $Cpu = 100, int $Mem = 1024, int $Disk = 5120, int $DiskSpeed = 5, int $NetworkSpeed = 1): array
    {
        $SID = Process::getMySQL()->countServers() + 1;
        $Directory = Process::getBase() . '/servers/server' . $SID;
        @system('useradd -m -d '.$Directory.' server'.$SID.' 2>&1');
       //@mkdir($Directory,0755);
       //@system('chown -R server'.$SID.':server'.$SID.' '.$Directory);
        $Key = Process::getMySQL()->newServer($Directory, $GameType, $Cpu, $Mem, $Disk, $DiskSpeed, $NetworkSpeed);
        $servers[$SID] = new Server($SID, $Key, $Directory, $GameType, $Cpu, $Mem, $Disk, $DiskSpeed, $NetworkSpeed, true);
        return array('SID' => $SID, 'Directory' => $Directory);
    }
}