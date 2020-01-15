<?php


class Autoload
{
    private static $_dirList = [];
    public static function loadClass($class)
    {
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        if (class_exists($class, false) ||
        interface_exists($class, false) ||
        trait_exists($class, false)) {
            return true;
        }
        $arr = preg_split("/(?=(Controller|Model|Middle|Controller))/", $class);
        foreach ($arr as $key=>$value) {
            if (!$value) {
                unset($arr[$key]);
            }
        }
        if (count($arr) > 1) {
            $path = end($arr);
            require_once $path . DIRECTORY_SEPARATOR . "{$class}.php";
            return true;
        } else {
            require_once "{$class}.php";
            return true;
        }
    }

    public static function load()
    {
        spl_autoload_register("Autoload::loadClass");
    }
    public static function ScanDirList($path){
        if(is_dir($path)) {
            $dir =  scandir($path);
            foreach ($dir as $value){
                $sub_path =$path .'/'.$value;
                if($value == '.' || $value == '..'){
                    continue;
                }else if(is_dir($sub_path)){
                    self::$_dirList[] = $sub_path;
                    self::ScanDirList($sub_path);
                }
            }
        }
    }
}