<?php
namespace Amadeus\Game\Minecraft\PocketMine;

use Amadeus\Plugin\Game\GameController;
use Amadeus\Plugin\Listener\GameListener;

/**
 * @Deprecated 你妈的要说多少遍写成插件啊，而且如果你要插件放在amadeus里的话，命名空间也不能是Amadeus\Game\MineCraft\PocketMine 如果你非要自带pm支持的话我就去写一个自动引导
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

    public function onServerStart($sid)
    {
        // TODO: Implement onServerStart() method.
    }

    public function onServerStop($sid)
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