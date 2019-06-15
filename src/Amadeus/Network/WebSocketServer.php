<?php


namespace Amadeus\Network;


use Amadeus\Config\Config;
use Amadeus\IO\Logger;
use Amadeus\Process;
use Amadeus\Runtime\Runtime;
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
        $this->server = new Server($this->server_ip, $this->server_port, SWOOLE_BASE, SWOOLE_TCP);
        $this->server->set(array(
            'worker_num' => $this->server_workers,
            'task_worker_num' => 1
        ));
        $this->server->on('task', function ($server, $task_id, $from_id, $data) {
            @cli_set_process_title('Amadeus Daemon Task Worker Process');
            Logger::printLine('Swoole task worker started', Logger::LOG_SUCCESS);
            if (!Runtime::processExists('TaskWorker' . $task_id)) {
                Runtime::registerProcessID('TaskWorker' . $task_id, posix_getpid());
                if ($data == 'tick') {
                    while (file_exists(Process::getBase() . '/cache/runtime/Daemon.pid')) {
                        Process::getPluginManager()->trigger('onServerTick');
                        sleep(1);
                    }
                }
                Runtime::deleteProcessID('TaskWorker' . $task_id);
            }
            $server->finish("{$data} -> finished");
        });

        $this->server->on('finish', function ($server, $task_id, $data) {
            Logger::printLine('Swoole task worker stopped', Logger::LOG_INFORM);
            @unlink(Process::getRuntime() . '/TaskWorker' . $task_id . '.pid');
        });
        $this->server->on('WorkerStart', function ($server, $workerId) {
            @cli_set_process_title('Amadeus Daemon Websocket Worker Process' . $workerId);
            Logger::printLine('Starting Websocket Worker Process ' . $workerId, Logger::LOG_INFORM);
            file_put_contents(Process::getRuntime() . '/WsWorker' . $workerId . '.pid', posix_getpid());
            if ($workerId == 0) {
                $server->task('tick');
            }
        });
        $this->server->on('WorkerStop', function ($server, $workerId) {
            Logger::printLine('Stopping Websocket Worker Process ' . $workerId, Logger::LOG_INFORM);
            @unlink(Process::getRuntime() . '/WsWorker' . $workerId . '.pid');
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

    public function stop(): bool
    {
        Logger::PrintLine('Stopping Websocket server', Logger::LOG_SUCCESS);
        $this->server->stop();
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