<?php


namespace Amadeus\Game;


use Amadeus\IO\Logger;

/**
 * Class GameControl
 * @package Amadeus\Game
 */
class GameControl
{
    /**
     * @var array
     */
    private $types = array();

    /**
     * GameControl constructor.
     */
    public function __construct()
    {
        Logger::printLine('Successfully registered', Logger::LOG_INFORM);
    }

    /**
     * @param string $type
     * @param object $reference
     * @return bool
     */
    public function onGameTypeRegister(string $type, object $reference): bool
    {
        if(array_key_exists($type, $this->types)){
            Logger::printLine('The same server controller appears and will be replaced later; We call this "バイツァ・ダスト(bite the dust)"', Logger::LOG_DANGER);
        }
        $this->types[$type] = $reference;
        return true;
    }

    /**
     * @param string $type
     * @return bool|mixed
     */
    public function getGameType(string $type)
    {
        return array_key_exists($type, $this->types) ? $this->types[$type] : false;
    }
}