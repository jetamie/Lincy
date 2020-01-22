<?php
/**
 * 当前为web入口
 */
//自动加载类
require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Bootstrap.php';
//模板引擎
include_once APP . 'vendor/smarty/bootstrap.php';
//web路由
include_once APP . 'router/business/route.php';