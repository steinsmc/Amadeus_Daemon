<?php


namespace Amadeus\Plugin;


use Amadeus\IO\Logger;
use Amadeus\Process;

/**
 * Class PluginManager
 * @package Amadeus\Plugin
 */
class PluginManager
{
    /**
     * @var array
     */
    private $listeners = array();
    /**
     * @var array
     */
    private $plugins = array();

    /**
     * PluginManager constructor.
     */
    public function __construct()
    {
        Logger::printLine('Loading plugins', Logger::LOG_SUCCESS);
        $items = array_diff(scandir('plugins/'), array('..', '.'));
        foreach ($items as $item) {
            if (is_file('plugins/' . $item) && substr($item, -5) === '.phar') {
                $info = yaml_parse(file_get_contents('phar://plugins/' . $item . '/plugin.yaml'));
                $this->plugins[$info['namespace']] = array('name' => $info['name'], 'uri' => 'phar://' . Process::getBase() . '/plugins/' . $item, 'stub' => $info['stub'], 'namespace' => $info['namespace'], 'main' => $info['main'], 'type' => 'phar');
            } else {
                $info = yaml_parse_file('plugins/' . $item . '/plugin.yaml');
                $this->plugins[$info['namespace']] = array('name' => $info['name'], 'uri' => Process::getBase() . '/plugins/' . $item, 'stub' => $info['stub'], 'main' => $info['main'], 'namespace' => $info['namespace'], 'type' => 'dir');
            }
            Logger::printLine('Found ' . $item, Logger::LOG_INFORM);
        }
        Logger::printLine('Successfully registered', Logger::LOG_INFORM);
    }

    /**
     * @return bool
     */
    public function start(): bool
    {
        if (count($this->plugins) > 0 && is_array($this->plugins)) {
            foreach ($this->plugins as $plugin) {
                //doesn't support phar autoload
                Process::getLoader()->setPsr4($plugin['namespace'], $plugin['uri'] . '/' . $plugin['stub'] . '/');
                include_once($plugin['uri'] . '/' . $plugin['stub'] . '/' . $plugin['main'] . '.php');
                $class_name = $plugin['namespace'] . $plugin['main'];
                Logger::printLine('Loading ' . $plugin['name'], Logger::LOG_INFORM);
                if (class_exists($class_name)) {
                    $reference = new $class_name($plugin['uri']);
                    $this->listeners[$reference->getName()] = $reference;
                    Logger::printLine('Registering ' . $reference->getName(), Logger::LOG_INFORM);
                    !method_exists($reference, 'onLoading') ?: $reference->onLoading();
                }
            }
        }
        $this->trigger('onLoaded');
        Logger::printLine('All plugins loaded', Logger::LOG_INFORM);
        return true;
    }

    /**
     * @Deprecacted
     * @param $reference
     * @param $name
     * @return bool
     */
    public function register($reference, $name): bool
    {
        Logger::printLine('Registering ' . $name, Logger::LOG_INFORM);
        $this->listeners[$name] = $reference;
        return true;
    }

    /**
     * @param string $event
     * @param null $data
     * @return bool
     */
    public function trigger(string $event, $data = null): bool
    {
        foreach ($this->listeners as $listener) {
            if (method_exists($listener, $event)) {
                if ($data === null) {
                    $listener->$event();
                } else {
                    $listener->$event($data);
                }
            }
        }
        return true;
    }

    /**
     * @param string $type
     * @param object $reference
     * @return bool
     */
    public function registerGameType(string $type, object $reference): bool
    {
        Logger::printLine('Registering game controller ' . $type, Logger::LOG_INFORM);
        Process::getGameControl()->onGameTypeRegister($type, $reference);
        return true;
    }
}