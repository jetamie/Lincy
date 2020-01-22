<?php
/**
 * 路由寻址文件
 */
$router = require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.php';
use router\business\Router;
Router::init();
Router::parseRouter($router);
Router::handle();
