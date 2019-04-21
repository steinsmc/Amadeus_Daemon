<?php


namespace Amadeus\Plugin\Listener;


/**
 * Interface Listener
 * @package Amadeus\Plugin\Listener
 */
interface Listener
{
    /**
     * @return mixed
     */
    public function onLoading();

    /**
     * @return mixed
     */
    public function onLoaded();
}