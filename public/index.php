<?php
/**
 * 当前为web入口
 */
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "Bootstrap.php";
define("DEBUG", false);
//Router::request();
include_once APP.'router/route.php';