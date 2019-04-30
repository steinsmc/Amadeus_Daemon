<?php


namespace Amadeus\Plugin\Listener;


interface GameListener extends Listener
{
    public function onServerStart(int $sid):int;
    public function onServerStop(int $sid):bool;
    public function onClientGetLog(int $sid);
}