<?php
namespace Amadeus\Game\MineCraft\PocketMine;

use Amadeus\Plugin\Game\GameController;
use Amadeus\Plugin\Listener\GameListener;

/**
 * Class PocketMine
 */
class PocketMine extends GameController implements GameListener {


    public function getConsole(){

    }


    public function getServerType()
    {
        // TODO: Implement getServerType() method.
    }

    public function initServer($sid)
    {
        // TODO: Implement initServer() method.
    }

    public function onServerStart($pid)
    {
        // TODO: Implement onServerStart() method.
    }

    public function onServerStop($pid)
    {
        // TODO: Implement onServerStop() method.
    }

    public function onClientGetLog()
    {
        // TODO: Implement onClientGetLog() method.
    }

    /**
     * @return mixed
     */
    public function onLoading()
    {
        // TODO: Implement onLoading() method.
    }

    /**
     * @return mixed
     */
    public function onLoaded()
    {
        // TODO: Implement onLoaded() method.
    }

    /**
     * PluginBase constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return "PocketMine";
    }
}