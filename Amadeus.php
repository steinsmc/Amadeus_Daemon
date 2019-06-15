<?php
declare(strict_types=1);

namespace {

    use Amadeus\IO\Logger;
    use Amadeus\Process;
    use Amadeus\Runtime\Runtime;

    if (PHP_OS == 'Darwin') {
        exit('Error: MacOS is still unsupported, please wait for the announcement.');
    }
    if (PHP_OS == 'WINNT') {
        exit('Error: Windows is unsupported and will not be supported in the future.');
    }
    if (PHP_OS == 'FreeBSD') {
        exit('Error: FreeBSD is still unsupported, please wait for the announcement.');
    }

    $_BASE = empty(Phar::running(false)) ? __DIR__ : dirname(Phar::running(false));
    require_once($_BASE.'/src/Amadeus/Runtime/Runtime.php');
    Amadeus\Runtime\Runtime::register($_BASE.'/cache/runtime');
    foreach ($argv as $arg) {
        switch ($arg) {
            case '-s':
                Amadeus\Runtime\Runtime::deleteProcessID('Daemon');
                $pid = @file_get_contents($_BASE . '/Amadeus.pid');
                if (!empty($pid)) {
                    @system('kill ' . $pid . ' >/dev/null 2>&1');
                    $x = 0;
                    while (true) {
                        system('kill -0 ' . $pid . ' >/dev/null 2>&1', $ret);
                        if ($ret != 0) {
                            break;
                        }
                        $x++;
                        if ($x > 30) {
                            echo "Performing a force kill" . PHP_EOL;
                            system('killall php');
                        }
                        sleep(1);
                    }
                    @unlink($_BASE . '/Amadeus.pid');
                }
                exit;
                break;
            case '-r':
                Amadeus\Runtime\Runtime::deleteProcessID('Daemon');
                $pid = @file_get_contents($_BASE . '/Amadeus.pid');
                if (!empty($pid)) {
                    @system('kill ' . $pid . ' >/dev/null 2>&1');
                    $x = 0;
                    while (true) {
                        system('kill -0 ' . $pid . ' >/dev/null 2>&1', $ret);
                        if ($ret != 0) {
                            break;
                        }
                        $x++;
                        if ($x > 30) {
                            echo "Performing a force kill" . PHP_EOL;
                            system('killall php');
                        }
                        sleep(1);
                    }
                    @unlink($_BASE . '/Amadeus.pid');
                }
                break;
            default:
                break;
        }
    }
    echo 'Amadeus Daemon Starting...' . PHP_EOL;
    @unlink($_BASE . '/Amadeus.log');
    if (file_exists($_BASE . '/Amadeus.pid')) {
        exit('Error: Amadeus Daemon already running' . PHP_EOL);
    }
    if (posix_geteuid() === 0) {
//        echo 'Error: You should not run Amadeus with root user' . PHP_EOL;
    }
    umask(0);
    $pid = pcntl_fork();
    if ($pid < 0) {
        exit('Error: Fork Failed ' . $pid . PHP_EOL);
    } else if ($pid > 0) {
        exit;
    }
    if (!posix_setsid()) {
        exit('Error: Setsid Failed ' . PHP_EOL);
    }
    $pid = pcntl_fork();
    if ($pid < 0) {
        exit('Error: Fork Failed ' . $pid . PHP_EOL);
    } else if ($pid > 0) {
        @file_put_contents($_BASE . '/Amadeus.pid', $pid);
        Amadeus\Runtime\Runtime::registerProcessID('Daemon', $pid);
        exit;
    }
    @cli_set_process_title('Amadeus Daemon');
    chdir($_BASE);
    echo 'Amadeus Daemon Started!' . PHP_EOL;
    ob_start();
    register_shutdown_function(function () use ($_BASE) {
        Amadeus\IO\Logger::printLine('Stopping the Daemon...');
        Amadeus\IO\Logger::printLine(ob_get_contents());
        ob_end_clean();
        Amadeus\Runtime\Runtime::deleteProcessID('Daemon');
        Amadeus\Process::unload();
        while ($ret = \Swoole\Process::wait(true)) {
            Logger::printLine("pid={$ret['pid']}", Logger::LOG_INFORM);
        }
        while (count(array_diff(scandir($_BASE . '/cache/runtime/'), array('..', '.'))) !== 0) {
            sleep(1);
        }
        @unlink($_BASE . '/Amadeus.pid');
    });
}

namespace Amadeus {

    use Phar;

    @mkdir('plugins', 0755);
    @mkdir('servers', 0755);
    @mkdir('cache', 0777);
    @mkdir('cache/runtime', 0777);
    $loader = require('vendor/autoload.php');
    Process::init(empty(Phar::running(false)) ? __DIR__ : dirname(Phar::running(false)), $loader);
}