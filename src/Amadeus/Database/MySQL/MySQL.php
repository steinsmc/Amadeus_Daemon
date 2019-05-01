<?php


namespace Amadeus\Database\MySQL;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use mysqli;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use Exception;

/**
 * Class MySQL
 * @package Amadeus\Database\MySQL
 */
class MySQL
{
    /**
     * @var mysqli
     */
    private $MySQL;

    /**
     * MySQL constructor.
     */
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
        }
        foreach (Tables::getTables() as $table) {
            $this->MySQL->query($table);
        }
        Logger::printLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    /**
     * @param string $Directory
     * @param string $GameType
     * @param int $Cpu
     * @param int $Mem
     * @param int $Disk
     * @param int $DiskSpeed
     * @param int $NetworkSpeed
     * @return bool
     */
    public function newServer(string $Directory, string $GameType, int $Cpu, int $Mem, int $Disk, int $DiskSpeed, int $NetworkSpeed)
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
        $sql->bind_param('sssiiiiii', $uuid, $Directory, $GameType, $Cpu, $Mem, $Disk, $DiskSpeed, $NetworkSpeed, $Status);
        $sql->execute();
        if (!empty($sql->error)) {
            Logger::printLine($sql->error, Logger::LOG_DEADLY);
            return false;
        }
        Logger::printLine('Succeed', Logger::LOG_SUCCESS);
        return $uuid;
    }

    /**
     * @return array
     */
    public function getServers(): array
    {
        $sql = $this->MySQL->prepare(StateMents::getStatement('getServers'));
        $sql->bind_result($RSID, $RKey, $RDirectory, $RGameType, $RCpu, $RMem, $RDisk, $RDiskSpeed, $RNetworkSpeed, $RStatus);
        $sql->execute();
        if (!empty($sql->error)) {
            return [];
        }
        $list = array();
        while ($sql->fetch()) {
            $list[$RSID] = array('SID' => $RSID, 'Key' => $RKey, 'Directory' => $RDirectory, 'GameControl' => $RGameType, 'Cpu' => $RCpu, 'Mem' => $RMem, 'Disk' => $RDisk, 'DiskSpeed' => $RDiskSpeed, 'NetworkSpeed' => $RNetworkSpeed, 'Status' => $RStatus);
        }
        $sql->free_result();
        return $list;
    }

    public function countServers(): int
    {
        $sql = $this->MySQL->prepare(StateMents::getStatement('countServers'));
        $sql->bind_result($RSID);
        $sql->execute();
        if (!empty($sql->error)) {
            return 0;
        }
        $list = 0;
        while ($sql->fetch()) {
            $list = $RSID;
        }
        $sql->free_result();
        if(is_null($list)){
            $list=0;
        }
        return $list;
    }

    public function delServerBySID(int $sid): bool
    {
        $sql = $this->MySQL->prepare(StateMents::getStatement('delServerBySID'));
        $sql->bind_param('i', $sid);
        $sql->execute();
        if (!empty($sql->error)) {
            Logger::printLine($sql->error, Logger::LOG_DEADLY);
            return false;
        }
        Logger::printLine('Succeed', Logger::LOG_SUCCESS);
        return true;
    }
}