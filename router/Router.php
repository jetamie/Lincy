<?php
namespace router;
use Vendor\router\Router as Route;

class Router
{
    /**
     * @var Route
     */
    private static $_route = null;


    public static function init()
    {
        if (!self::getUri()) {
            header('Location:/index/index');
            exit;
        }
        if (!self::$_route) {
            self::$_route = new Route();
        }
    }

    /**
     * GET
     * @param $uri
     * @param $name
     * @param $action
     */
    public static function get($uri, $action, $name=null)
    {
        self::$_route->get($uri,$action, $name);
    }

    /**
     * POST
     * @param $uri
     * @param $name
     * @param $action
     */
    public static function post($uri, $action, $name=null)
    {
        self::$_route->post($uri,$action, $name);
    }

    /**
     * PUT
     * @param $uri
     * @param $name
     * @param $action
     */
    public static function put($uri, $action, $name=null)
    {
        self::$_route->put($uri,$action, $name);
    }

    /**
     * DELETE
     * @param $uri
     * @param $name
     * @param $action
     */
    public static function delete($uri, $action, $name=null)
    {
        self::$_route->delete($uri, $action, $name);
    }

    /**
     * @param $action
     * @param $params
     */
    private static function matchAction($action, $params)
    {
        if (empty($action)) {
            exit('参数不能为空');
        }
        if (substr($action, 0, 1) == '/') {
            $action = substr($action,  1);
        }
        $param = explode('/', $action);
        //路由长度不能少于2
        if (count($param) < 2) {
            exit('参数不正确');
        }
        //实例化方法
        $className = isset($param[0])&&$param[0]?lcfirst($param[0])."Controller":"indexController";
        $method = isset($param[1])&&$param[1]?lcfirst($param[1]):"index";
        $className = '\\app\\business\\Controller\\'.$className;
        if (class_exists($className)) {
            if (method_exists($className, $method)) {
                $obj = new $className();
                $obj->init($params);
                $data = $obj->$method();
                echo json_encode($data);
                return;
            } else {
                exit('方法不存在');
            }
        } else {
            exit('控制器不存在');
        }
    }

    /**
     * 暴露数据
     */
    public static function handle()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $uri = self::getUri();
        $res = self::$_route->match($uri, $method);
        if (!$res) {
            exit(json_encode(['RouterException'=>'Method='.$method.'未找到`'.$uri.'`的路由']));
        }
        $params = $res->getParams();
        $action = $res->getStorage();
        self::matchAction($action, $params);
    }
    /**
     * 获取uri
     * @return mixed
     */
    private static function getUri()
    {
        $param = $_SERVER['PHP_SELF'];
        $self = str_replace('/index.php', '', $param);
        return $self;
    }
}