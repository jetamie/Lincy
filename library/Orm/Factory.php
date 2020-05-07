<?php


namespace library\Orm;


use package\Config;

class Factory
{

    private static $_redis;
    private static $_mysql;
    private static $_mongo;
    private static $_mongo7;
    public static function getMysqlInstance($config = 'system.mysql')
    {
        try {
            if (!self::$_mysql) {
                $table = new Table(new Mysql($config));
                self::$_mysql = $table->getAdapter();
            }
            return self::$_mysql;
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public static function getRedisInstance($config = 'system.redis')
    {
        if (!self::$_redis) {
            $conf = Config::get($config);
            if ($conf) {
                self::$_redis = new \Redis();
                self::$_redis->connect($conf['host'],$conf['port']);
                self::$_redis->auth($conf['pass']);
            }
        }
        return self::$_redis;
    }

    public static function getMongoInstance($config = 'system.mongo')
    {
        if (!self::$_mongo) {
            $conf = Config::get($config);
            if ($conf) {
                try {
                    $link = new \MongoClient('mongodb://'.$conf['host'].':'.$conf['port']);
                    $table = new Table(new Mongo($link));
                } catch (\Exception $e) {
                    throw $e;
                }
                self::$_mongo = $table->getAdapter();
            }
        }
        return self::$_mongo;
    }

    public static function getMongo7Instance($config = 'system.mongo')
    {
        if (!self::$_mongo7) {
            $conf = Config::get($config);
            if ($conf) {
                try {
                    $link = new \MongoDB\Driver\Manager('mongodb://'.$conf['host'].':'.$conf['port']);
                    $table = new Table(new Mongo($link));
                } catch (\Exception $e) {
                    throw $e;
                }
                self::$_mongo7 = $table->getAdapter();
            }
        }
        return self::$_mongo7;
    }
}