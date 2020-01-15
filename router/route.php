<?php
/**
 * 路由寻址文件
 */
use router\Router;
Router::init();
Router::get('/index/index/:name?', '/index/index');
Router::get('/app/index/:id/:name?', '/index/index');
Router::handle();
