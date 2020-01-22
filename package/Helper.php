<?php
namespace package;

use library\Middleware\Handle;

class Helper
{
    public static function middleware(...$middle)
    {
        if (!$middle) return;
        $m = new Handle();
        foreach ($middle as $mid) {
            if (is_object($mid) && get_parent_class($mid) == 'library\Middleware\Interfacer') {
                $m->addMiddleware($mid);
            }
        }
        $m->handle();
    }

    /**
     * 检查数据深度
     * @param $array
     * @param $limit
     */
    public static function CheckPool(&$array,$limit = 10)
    {
        if (count($array) > $limit) {
            array_shift($array);
        }
    }
}