<?php


class Autoloader
{
    public static function loadClass($class)
    {
        if (class_exists($class, false) ||
            interface_exists($class, false) ||
            trait_exists($class, false)) {
            return true;
        }
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        @include_once $class.'.php';
        return true;
    }

    public static function load()
    {
        spl_autoload_register("Autoloader::loadClass");
    }
}