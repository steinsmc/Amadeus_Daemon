<?php


namespace Amadeus\Plugin\Game;


use Amadeus\Plugin\Basic\PluginBase;

/**
 * Class GameController
 * @package Amadeus\Plugin\Game
 */
abstract class GameController extends PluginBase
{
    /**
     * @return string
     */
    public abstract function getServerType(): string;

    /**
     * @param int $sid
     * @return bool
     */
    public abstract function initServer(int $sid): bool;

    /**
     * @param int $sid
     * @return bool
     */
    public abstract function onServerStop(int $sid): bool;

    /**
     * @param int $sid
     * @return bool
     */
    public abstract function finServer(int $sid): bool;
}