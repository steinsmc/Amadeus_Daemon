<?php


namespace Amadeus\Database\MySQL;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use mysqli;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use Exception;

class MySQL
{
    private $MySQL;

    public function __construct()
    {
        if (is_null(Config::get('daemon_mysql_sock'))) {
            Logger::printLine('Not using socket', Logger::LOG_INFORM);
            $this->MySQL = new mysqli(Config::get('daemon_mysql_host'), Config::get('daemon_mysql_user'), Config::get('daemon_mysql_password'), Config::get('daemon_mysql_dbname'), Config::get('daemon_mysql_port'));
        } else {
            Logger::printLine('Using socket', Logger::LOG_INFORM);
            $this->MySQL = new mysqli(Config::get('daemon_mysql_host'), Config::get('daemon_mysql_user'), Config::get('daemon_mysql_password'), Config::get('daemon_mysql_dbname'), Config::get('daemon_mysql_port'), Config::get('daemon_mysql_sock'));
        }
        $this->MySQL->set_charset("utf8");
        if ($this->MySQL->connect_error) {
            Logger::PrintLine('Failed to connect to MySQL server', Logger::LOG_FATAL);
            return false;
        }
        foreach (Tables::getTables() as $table) {
            $this->MySQL->query($table);
        }
        Logger::printLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    public function newServer($Directory, $Cpu, $Mem, $Disk, $DiskSpeed, $NetworkSpeed)
    {
        try {
            $uuid = Uuid::uuid4()->__toString();
        } catch (UnsatisfiedDependencyException $e) {
            //This should not be happening!!!
            Logger::printLine('貴様何故こんな事を', Logger::LOG_FATAL);
            return false;
        } catch (Exception $e) {
            Logger::printLine('Unable to Generate UUID', Logger::LOG_DEADLY);
            return false;
        }
        $Status = 1;
        Logger::printLine('UUID Generated: ' . $uuid, Logger::LOG_INFORM);
        $sql = $this->MySQL->prepare(StateMents::getStatement('newServer'));
        $sql->bind_param("ssiiiiii", $uuid, $Directory, $Cpu, $Mem, $Disk, $DiskSpeed, $NetworkSpeed, $Status);
        $sql->execute();
        if (!empty($sql->error)) {
            Logger::printLine($sql->error, Logger::LOG_DEADLY);
            return false;
        }
        Logger::printLine('Succeed', Logger::LOG_SUCCESS);
        return true;
    }
    public function getServers(){
        $sql = $this->MySQL->prepare(StateMents::getStatement('getServers'));
        $sql->bind_result($RSID,$RKey, $RDirectory, $RCpu, $RMem, $RDisk, $RDiskSpeed, $RNetworkSpeed, $RStatus);
        $sql->execute();
        if (!empty($sql->error)) {
            return false;
        }
        $list = array();
        while ($sql->fetch()) {
            $list[$RSID] = array('SID'=>$RSID,'Key'=>$RKey,'Directory'=>$RDirectory,'Cpu'=>$RCpu,'Mem'=>$RMem,'Disk'=>$RDisk,'DiskSpeed'=>$RDiskSpeed,'NetworkSpeed'=>$RNetworkSpeed,'Status'=>$RStatus);
        }
        $sql->free_result();
        return $list;
    }
}