<?php


namespace Amadeus\Plugin\Listener;


interface GameListener extends Listener
{
    public function onServerStart($pid);
    public function onServerStop($pid);
    public function onClientGetLog();
}