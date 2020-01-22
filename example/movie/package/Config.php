<?php


namespace package;

use library\Config as IConfig;

class Config implements IConfig
{
    /**
     * 默认路径
     * @var string
     */
    private static $_path = CONFIG;
    /**
     * @param string $param
     * @return mixed
     */
    public static function get($param)
    {
        // TODO: Implement get() method.
        if (empty($param)) return false;
        $arr = explode('.', $param);
        $ini = $arr[0].'.ini';
        if (file_exists(self::$_path.'/'.$ini)) {
            $config = parse_ini_file(self::$_path . '/' . $ini, true);
        } else {
            return false;
        }
        $res = '';
        (isset($arr[1])) && (isset($config[$arr[1]]) && $res = $config[$arr[1]]);
        (isset($arr[2])) && (isset($config[$arr[1]][$arr[2]]) && $res = $config[$arr[1]][$arr[2]]);
        if (!$res) return $config;
        return $res;
    }

    /**
     * @param string $param
     * @return bool
     */
    public static function set($param)
    {
        // TODO: Implement set() method.
        return  true;
    }

    /**
     * @param string $path
     * @return mixed
     */
    public static function setPath($path)
    {
        // TODO: Implement setPath() method.
        if (is_dir($path)) {
            self::$_path = $path;
        }
    }
}