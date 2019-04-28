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
     * @param $directory
     */
    public abstract function __construct($directory);

    /**
     * @return mixed
     */
    public abstract function getName();
}