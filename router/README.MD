## 中间件文件目录

> 使用方法

```shell script
use router\Router;
Router::init();
Router::get('/index/index/:name?', '/index/index');
Router::get('/app/index/:id/:name?', '/index/index');
Router::handle();
```
该路由作者：https://github.com/nauxliu/route，在原来基础上简单封装
