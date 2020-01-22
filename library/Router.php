<?php
namespace library;
/**
 * 路由器-通过正则匹配路由方式
 * Class Router
 */
class Router
{
    /**
     * @param $param
     * @param $debug
     * @return void (Controller|Model)
     */
    public static function dispatch($param, $debug = false)
    {
        self::_init($debug);
        if (isset($param[1]) && $param[1]) {
            $class = explode("/", $param[1]);
            $className = '\\app\\console\\Controller\\'.$class[0]."Console";
            $method = isset($class[1])?$class[1]:"index";
            if (class_exists($className)) {
                $class = new $className();
                if (method_exists($class, $method)) {
                       //初始化参数
                       $class->init(array_slice($param, 1));
                       //初始化模型
                       $class->initModel();
                       $data = $class->$method();
                       //可重写handle
                       $class->handle($data);
                       return;
                }
                exit("方法不存在");
            }
            exit("Console不存在!");
        } else {
            exit("Console为空!");
        }
    }

    /**
     * url router
     * @param $debug
     */
    public static function request($debug = false)
    {
        self::_init($debug);
        $param = $_SERVER['PHP_SELF'];
        $param = explode("/", $param);
        array_shift($param);
        $index = isset($param[0])&&$param[0]?lcfirst($param[0]):"";
        $data = [
            "code" => "-1",
            "msg" => "少了入口文件index.php"
        ];
        (!$index) && (exit(json_encode($data)));
        $className = isset($param[1])&&$param[1]?lcfirst($param[1])."Controller":"indexController";
        $method = isset($param[2])&&$param[2]?lcfirst($param[2]):"index";
        $className = '\\app\\business\\Controller\\'.$className;
        if (class_exists($className)) {
            $class = new $className();
            if (method_exists($class, $method)) {
                $class->init();
                $data = $class->$method();
                if ($data) {
                    echo json_encode($data);
                }
                exit;
            }
            $data["msg"] = "方法不存在";
            exit(json_encode($data));
        }
        $data["msg"] = "控制器不存在";
        exit(json_encode($data));
    }

    /**
     * 初始化操作
     * @param $debug
     */
    private static function _init($debug = true)
    {
        if ($debug) {
            ini_set("display_errors", true);
            error_reporting(E_ALL);
        } else {
            error_reporting(0);
        }
    }
}