<?php
/**
 * 路由寻址文件
 */
use router\Router;
Router::init();
Router::get('/index/index/', '/index/index');
Router::get('/v1/api', '/index/api');
Router::handle();
