<?php


namespace Amadeus\Plugin\Listener;


interface GameListener extends Listener
{
    public function onServerStart($sid);
    public function onServerStop($sid);
    public function onClientGetLog();
}