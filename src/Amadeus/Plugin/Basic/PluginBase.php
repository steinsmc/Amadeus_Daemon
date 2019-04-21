<?php


namespace Amadeus\Plugin\Basic;


/**
 * Class PluginBase
 * @package Amadeus\Plugin\Basic
 */
abstract class PluginBase
{
    /**
     * PluginBase constructor.
     */
    public abstract function __construct();

    /**
     * @return mixed
     */
    public abstract function getName();
}