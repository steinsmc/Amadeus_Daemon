<?php


namespace Amadeus\Environment;

use Amadeus\Environment\Backend\Server;
use Amadeus\IO\Logger;
use Amadeus\Process;

class ServerManager
{
    private $servers;

    public function __construct()
    {
        $servers = Process::getMySQL()->getServers();
        foreach($servers as $SID=>$server){
            $servers[$SID]=new Server($server['SID'],$server['Key'],$server['Directory'],$server['Cpu'],$server['Mem'],$server['Disk'],$server['DiskSpeed'],$server['NetworkSpeed'],$server['Status']);
        }
        Logger::PrintLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    public function newServer(): array
    {
        return array();
    }
}