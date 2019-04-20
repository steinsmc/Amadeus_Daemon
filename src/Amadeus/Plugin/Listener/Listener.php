<?php


namespace Amadeus\Plugin\Listener;


interface Listener
{
    public function onLoading();
    public function onLoaded();
}