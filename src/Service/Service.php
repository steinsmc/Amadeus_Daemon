<?php

//この街に生まれたのは、あなたと巡り逢うため。この街に生まれたから、あなたに巡り逢えた。

namespace Service {
    require_once('Autoloader.php');

    $_CONFIG = !empty(\Phar::running(false))?require_once(dirname(dirname(\Phar::running(false)))).'/Config/Config.php':require_once(dirname(dirname(__FILE__)).'/Config/Config.php');

    use \Service\App\Logger as Logger;

    Logger::register();
}