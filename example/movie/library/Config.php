<?php


namespace library;


interface Config
{
    /**
     * @param string $param
     * @return mixed
     */
    public static function get($param);

    /**
     * @param string $param
     * @return bool
     */
    public static function set($param);

    /**
     * @param string $path
     * @return mixed
     */
    public static function setPath($path);
}