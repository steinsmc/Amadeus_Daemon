<?php


namespace Amadeus\Plugin\Game;


use Amadeus\Plugin\Basic\PluginBase;

abstract class GameController extends PluginBase
{
    public abstract function getServerType();
    public abstract function initServer($sid);
    public abstract function finServer($sid);
}