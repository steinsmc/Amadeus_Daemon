<?php


namespace Amadeus\Environment;

use Amadeus\IO\Logger;
use Amadeus\Process;

class ServerManager
{
    private $servers;

    public function __construct()
    {
        $servers=Process::getMySQL()->getServers();
        Logger::PrintLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    public function generateServerEnvironment($name)
    {

    }
}