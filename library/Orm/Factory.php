<?php


namespace library\Orm;


use package\Config;

class Factory
{

    private static $_redis;
    private static $_mysql;
    private static $_mongo;
    public static function getMysqlInstance($config = 'system.mysql')
    {
        return self::$_mysql;
    }

    public static function getRedisInstance($config = 'system.redis')
    {
        if (!self::$_redis) {
            $conf = Config::get($config);
            if ($conf) {
                self::$_redis = new \Redis();
                self::$_redis->connect($conf["redis"]["host"],$conf["redis"]["port"]);
                self::$_redis->auth($conf["redis"]["pass"]);
            }
        }
        return self::$_redis;
    }

    public static function getMongoInstance($config = 'system.mongo')
    {
        return self::$_mongo;
    }
}