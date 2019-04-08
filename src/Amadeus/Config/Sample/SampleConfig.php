<?php


namespace Amadeus\Config\Sample;


class SampleConfig
{
    public static function generate()
    {
        return array(
            'daemon_db_username' => 'root',
            'daemon_db_password' => 'root',
            'daemon_db_name' => 'amadeus_daemon'
        );
    }
}