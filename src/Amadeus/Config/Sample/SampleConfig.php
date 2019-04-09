<?php


namespace Amadeus\Config\Sample;


class SampleConfig
{
    public static function generate()
    {
        return array(
            'daemon_db_username' => 'root',
            'daemon_db_password' => 'root',
            'daemon_db_name' => 'amadeus_daemon',
            'daemon_address' =>'127.0.0.1',
            'daemon_port' => '2333',
            'daemon_workers' => '4'
        );
    }
}