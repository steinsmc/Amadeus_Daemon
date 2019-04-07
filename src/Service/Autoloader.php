<?php

/*
* Author: dhdj
* date: March 28.2018
*/

//自动引导类
class Autoloader
{
    public static function Register()
    {
        spl_autoload_register(function ($class) {
            $file = str_replace('Service/', '', str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';
            //if(file_exists($file)){
            require $file;
            return true;
            //}
        });
    }
}

//静态方法注册
Autoloader::Register();