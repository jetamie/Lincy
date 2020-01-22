## 中间件文件目录

> 使用方法

```shell script
//映射关系uri=>action
return [
    'get' => [
        '/v1/api' => '/index/api',
        '/index/index/'=>'/index/index/'
    ],
    'post' => [
        '/v1/api' => '/index/api'
    ]
];
```

```shell script
$router = require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php';
use router\Router;
Router::init();
Router::parseRouter($router);
Router::handle();
```
该路由作者：https://github.com/nauxliu/route，在原来基础上简单封装
