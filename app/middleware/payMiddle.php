<?php
namespace app\middleware;

use library\Middleware\Interfacer;

class payMiddle extends Interfacer
{
    public function handle()
    {
        echo 'i\'m pay middle'.PHP_EOL;
    }
}