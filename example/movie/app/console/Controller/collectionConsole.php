<?php
namespace app\console\Controller;

use library\Core\Console;
use package\Config;
use package\Helper;

class collectionConsole extends Console
{
    /**
     * 默认方法
     * @return mixed
     */
    public function index()
    {
        $this->_model->run($this->_param);
    }
    public function updateWd()
    {
        $this->_model->updateWd($this->_param);
    }
}