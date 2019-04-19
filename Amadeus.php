<?php
declare(strict_types=1);

namespace {
    $_BASE = empty(Phar::running(false)) ? __DIR__ : dirname(Phar::running(false));
    foreach ($argv as $arg) {
        if ($arg === '-s') {
            @system('kill ' . @file_get_contents($_BASE . '/Amadeus.pid') . ' >/dev/null 2&>1');
            @unlink($_BASE . '/Amadeus.pid');
            exit;
        }
    }
    echo 'Amadeus Daemon Starting...' . PHP_EOL;
    @unlink($_BASE . '/Amadeus.log');
    if (file_exists($_BASE . '/Amadeus.pid')) {
        exit('Error: Amadeus Daemon already running' . PHP_EOL);
    }
    if (posix_geteuid() === 0) {
        echo 'Error: You should not run Amadeus with root user' . PHP_EOL;
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
        exit;
    }
    @cli_set_process_title('Amadeus Daemon');
    chdir($_BASE);
    echo 'Amadeus Daemon Started!' . PHP_EOL;
}

namespace Amadeus {

    use Amadeus\IO\Logger;
    use Phar;

    @mkdir('plugins');
    $loader = require('vendor/autoload.php');
    Process::init(empty(Phar::running(false)) ? __DIR__ : dirname(Phar::running(false)));
    Logger::printLine('Stopping the Daemon...');
    @unlink('Amadeus.pid');
}