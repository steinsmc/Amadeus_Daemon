<?php


namespace Amadeus\Game;


use Amadeus\IO\Logger;

class GameType
{
    private $types = array();

    public function __construct()
    {
        Logger::printLine('Successfully registered', Logger::LOG_INFORM);
    }

    public function onGameTypeRegister(string $type, object $reference): bool
    {
        $this->types[$type] = $reference;
        return true;
    }

    public function getGameType(string $type)
    {
        return array_key_exists($type,$this->types)?$this->types[$type]:false;
    }
}