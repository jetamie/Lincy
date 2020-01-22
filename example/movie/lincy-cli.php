<?php
/**
 * 当前为脚本控制器入口
 */
use router\console\Router;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "Bootstrap.php";
Router::dispatch($argv, true);