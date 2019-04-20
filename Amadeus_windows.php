<?php

namespace {
    echo 'Amadeus Daemon Starting...' . PHP_EOL;
    $_BASE = empty(Phar::running(false)) ? __DIR__ : dirname(Phar::running(false));
    @unlink($_BASE . '/Amadeus.log');
    @cli_set_process_title('Amadeus Daemon');
    chdir($_BASE);
    echo 'Amadeus Daemon Started!' . PHP_EOL;
}

namespace Amadeus {

    use Phar;
    use Amadeus\IO\Logger;

    @mkdir('plugins');
    $loader = require('vendor/autoload.php');
    Process::init(empty(Phar::running(false)) ? __DIR__ : dirname(Phar::running(false)),$loader);
    Logger::printLine('Stopping the Daemon...');
}