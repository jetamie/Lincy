<?php

define("APP", dirname(__FILE__).DIRECTORY_SEPARATOR);
define("CONFIG", APP."config".DIRECTORY_SEPARATOR);
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            get_include_path(),
            APP."library",
            APP."Controller",
            APP."package",
        )
    )
);
require_once APP . "Autoloader.php";
Autoloader::load();