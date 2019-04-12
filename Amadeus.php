<?php

namespace {
    echo 'Amadeus Daemon Starting...' . PHP_EOL;
    $_BASE = empty(Phar::running(false)) ? __DIR__ : dirname(Phar::running(false));
    @unlink($_BASE . '/Amadeus.log');
    if (file_exists('Amadeus.pid')) {
        exit('Error: Amadeus Daemon already running' . PHP_EOL);
    }
    if (posix_geteuid() === 0) {
        exit('Error: You should not run Amadeus with root user' .PHP_EOL);
    }
    umask(0);
    $pid = pcntl_fork();
    if ($pid < 0) {
        exit('Error: Fork Failed ' . $pid .PHP_EOL);
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
    @mkdir('plugins');
    $loader = require('vendor/autoload.php');
    Process::init();
    \Amadeus\IO\Logger::printLine('Stopping the Daemon...');
    @unlink('Amadeus.pid');
}