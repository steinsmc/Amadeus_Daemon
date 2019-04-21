<?php


namespace Amadeus\Plugin\Listener;


interface GameListener extends Listener
{
    public function onServerStart();
    public function onServerStop();
}