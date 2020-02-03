<?php
namespace router\business;
use library\Core\Controller;
use vendor\router\Router as Route;

class Router
{
    /**
     * @var Route
     */
    private static $_route = null;
    /**
     * @var Controller[]
     */
    private static $_container = [];


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
     * 自定义协议
     * @param $uri
     * @param $action
     * @param $name
     * @param string $method
     */
    public static function add($uri,$action, $name, $method = 'GET')
    {
        self::$_route->add($uri,$action, $name, $method);
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
        //增加缓存器
        if (isset(self::$_container[$className])) {
            $obj = self::$_container[$className];
        } else {
            if (class_exists($className)) {
                if (method_exists($className, $method)) {
                    $obj = new $className();
                    self::$_container[$className] = $obj;
                } else {
                    exit('方法不存在');
                }
            } else {
                exit('控制器不存在');
            }
        }
        //执行方法
        $obj->init($params);
        $data = $obj->$method();
        if ($data != null && $data != false && $data != '') {
            echo json_encode($data);
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

    /**
     * 解析路由文件
     * @param $config
     */
    public static function parseRouter($config)
    {
        if (empty($config) || !is_array($config)) {
            self::get('/index/index', '/index/index');
        }
        try {
            foreach ($config as $method => $map) {
                foreach ($map as $uri => $act) {
                    $method = strtolower($method);
                    self::$method($uri, $act);
                }
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}