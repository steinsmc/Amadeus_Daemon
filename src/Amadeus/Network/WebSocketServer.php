<?php


namespace Amadeus\Network;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use Amadeus\Process;
use Swoole\Coroutine;
use Swoole\WebSocket\Server as Server;

/**
 * Class WebSocketServer
 * @package Amadeus\Network
 */
class WebSocketServer
{
    /**
     * @var Server
     */
    /**
     * @var mixed|string|Server
     */
    /**
     * @var mixed|string|Server
     */
    /**
     * @var mixed|string|Server
     */
    private
        $server,
        $server_ip,
        $server_port,
        $server_workers;

    /**
     * WebSocketServer constructor.
     */
    public function __construct()
    {
        $this->server_ip = Config::get('daemon_address');
        $this->server_port = Config::get('daemon_port');
        $this->server_workers = Config::get('daemon_workers');
        if (empty($this->server_ip) or empty($this->server_port) or empty($this->server_workers)) {
            Logger::printLine('Failed to start websocket server...shutting down!', Logger::LOG_FATAL);
        }
        @unlink(Process::getCache() . '/TaskWorker.exist');
        $this->server = new Server($this->server_ip, $this->server_port, SWOOLE_BASE, SWOOLE_TCP);
        $this->server->set(array(
            'worker_num' => $this->server_workers,
            'task_worker_num' => 2
        ));
        $this->server->on('task', function ($server, $task_id, $from_id, $data) {
            Logger::printLine('Swoole task worker started', Logger::LOG_SUCCESS);
            if ($data == 'tick') {
                if (!file_exists(Process::getCache() . '/TaskWorker.exist')) {
                    file_put_contents(Process::getCache() . '/TaskWorker.exist', '');
                    while (file_exists(Process::getBase() . '/Amadeus.pid')) {
                        Process::getPluginManager()->trigger('onServerTick');
                        sleep(1);
                    }
                    @unlink(Process::getCache() . '/TaskWorker.exist');
                }
            }
            $server->finish("{$data} -> finished");
        });

        $this->server->on('finish', function ($server, $task_id, $data) {
            Logger::printLine('Swoole task worker stopped', Logger::LOG_INFORM);
        });
        $this->server->on('WorkerStart', function ($server, $workerId) {
            if ($workerId == 0) {
                $server->task('tick');
                Logger::printLine('Amadeus timer started', Logger::LOG_SUCCESS);
            }
        });
        $this->server->on('open', ['Amadeus\Network\Reactor', 'onOpen']);
        $this->server->on('message', ['Amadeus\Network\Reactor', 'onMessage']);
        $this->server->on('close', ['Amadeus\Network\Reactor', 'onClose']);
        Logger::PrintLine('Successfully registered', Logger::LOG_SUCCESS);
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        Logger::PrintLine('Starting Websocket server', Logger::LOG_SUCCESS);
        $this->server->start();
        return true;
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }
}