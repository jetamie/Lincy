<?php
namespace app\middleware;

use library\Middleware\Interfacer;

class testMiddle extends Interfacer
{

    public function before()
    {
        echo 'i\'m test middle before'.PHP_EOL;
    }

    public function handle()
    {
        // TODO: Implement handle() method.
        echo 'i\'m test middle'.PHP_EOL;
    }
}