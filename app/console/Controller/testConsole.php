<?php
namespace app\console\Controller;

use library\Core\Console;
use package\Config;
use package\Helper;
use app\middleware\testMiddle;
use app\middleware\payMiddle;

class testConsole extends Console
{

    /**
     * 默认方法
     * @return mixed
     */
    public function index()
    {
        Helper::middleware(new testMiddle(),new payMiddle());
    }

    public function test()
    {
        var_dump(Config::get('system.mysql.host'));
    }
}