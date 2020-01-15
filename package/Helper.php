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
}