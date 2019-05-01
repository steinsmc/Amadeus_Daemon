<?php


namespace Amadeus\Plugin\Game;


use Amadeus\Plugin\Basic\PluginBase;

abstract class GameController extends PluginBase
{
    public abstract function getServerType(): string;

    public abstract function initServer(int $sid): bool;

    public abstract function onServerStop(int $sid): bool;

    public abstract function finServer(int $sid): bool;
}