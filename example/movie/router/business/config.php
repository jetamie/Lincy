<?php
//映射关系uri=>action
return [
    'get' => [
        '/v1/api/:type?/:page?' => '/index/api',
        '/v1/search/:wd?' => '/index/search',
        '/movie/index'=>'/index/index/',
        '/movie/detail'=>'/index/detail/',
        '/movie/player'=>'/index/player/'
    ]
];
