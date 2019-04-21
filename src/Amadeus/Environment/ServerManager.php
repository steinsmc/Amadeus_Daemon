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
        foreach($servers as $SID=>$server){
            $servers[$SID]=new Server($server['SID'],$server['Key'],$server['Directory'],$server['GameType'],$server['Cpu'],$server['Mem'],$server['Disk'],$server['DiskSpeed'],$server['NetworkSpeed'],$server['Status']);
        }
        Logger::PrintLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    /**
     * @return array
     */
    public function newServer(): array
    {
        return array();
    }
}