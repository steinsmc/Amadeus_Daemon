<?php

namespace {
    echo 'Amadeus Daemon Starting...' . PHP_EOL;
    @unlink('Amadeus.log');
    if (file_exists('Amadeus.pid')) {
        exit('Error: Amadeus Daemon already running' . PHP_EOL);
    }
    if (posix_geteuid() === 0) {
        exit('Error: You should not run Amadeus with root user' . PHP_EOL);
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
        @file_put_contents('Amadeus.pid', $pid);
        exit;
    }
    @cli_set_process_title('Amadeus Daemon');
    chdir(__DIR__);
    echo 'Amadeus Daemon Started!' . PHP_EOL;
    $loader = require('vendor/autoload.php');
}

namespace Amadeus {
    Process::init();
    @unlink('Amadeus.pid');
}