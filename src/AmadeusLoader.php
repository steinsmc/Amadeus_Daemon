<?php

namespace {
    function safe_var_dump()
    {
        static $cnt = 0;
        foreach (func_get_args() as $var) {
            switch (true) {
                case is_array($var):
                    echo str_repeat("  ", $cnt) . "array(" . count($var) . ") {" . PHP_EOL;
                    foreach ($var as $key => $value) {
                        echo str_repeat("  ", $cnt + 1) . "[" . (is_int($key) ? $key : '"' . $key . '"') . "]=>" . PHP_EOL;
                        ++$cnt;
                        safe_var_dump($value);
                        --$cnt;
                    }
                    echo str_repeat("  ", $cnt) . "}" . PHP_EOL;
                    break;
                case is_int($var):
                    echo str_repeat("  ", $cnt) . "int(" . $var . ")" . PHP_EOL;
                    break;
                case is_float($var):
                    echo str_repeat("  ", $cnt) . "float(" . $var . ")" . PHP_EOL;
                    break;
                case is_bool($var):
                    echo str_repeat("  ", $cnt) . "bool(" . ($var === true ? "true" : "false") . ")" . PHP_EOL;
                    break;
                case is_string($var):
                    echo str_repeat("  ", $cnt) . "string(" . strlen($var) . ") \"$var\"" . PHP_EOL;
                    break;
                case is_resource($var):
                    echo str_repeat("  ", $cnt) . "resource() of type (" . get_resource_type($var) . ")" . PHP_EOL;
                    break;
                case is_object($var):
                    echo str_repeat("  ", $cnt) . "object(" . get_class($var) . ")" . PHP_EOL;
                    break;
                case is_null($var):
                    echo str_repeat("  ", $cnt) . "NULL" . PHP_EOL;
                    break;
            }
        }
    }
}

namespace Service {
    function namespace_var_get($Name)
    {
        if (isset($GLOBALS[$Name])) {
            return $GLOBALS[$Name];
        } else {
            return false;
        }
    }

    require_once('./Service/Service.php');
}
?>