<?php


namespace library\Orm;


use package\Config;
use package\Helper;

class Mysql implements Db
{
    private $_link = null;

    private static $_conn = [];

    public function __construct($link)
    {
        if (empty($link)) {
            throw new \Exception('链接为空!');
        }
        $this->_link = $link;
    }

    /**
     * 原生查询
     * @param $sql
     * @return mixed
     */
    public function query($sql)
    {
        // TODO: Implement query() method.
    }

    /**
     * 设置表名['user']
     * @param $table
     * @return mixed
     */
    public function table($table)
    {
        // TODO: Implement table() method.
    }

    /**
     * 查询条件['id'=>1]
     * @param array $where
     * @return mixed
     */
    public function where($where = [])
    {
        // TODO: Implement where() method.
    }

    /**
     * 限制条数
     * @param int $limit
     * @return mixed
     */
    public function limit($limit = 0)
    {
        // TODO: Implement limit() method.
    }

    /**
     * 排序['time'=>'desc|asc']
     * @param array $sort
     * @return mixed
     */
    public function sort($sort = [])
    {
        // TODO: Implement sort() method.
    }

    /**
     * 设置查询字段['id','name']
     * @param array $field
     * @return mixed
     */
    public function field($field = [])
    {
        // TODO: Implement field() method.
    }

    /**
     * 更新数据
     * @param $data
     * @return mixed
     */
    public function update($data)
    {
        // TODO: Implement update() method.
    }

    /**
     * 删除数据
     * @return mixed
     */
    public function delete()
    {
        // TODO: Implement delete() method.
    }

    /**
     * 查询数据
     * @return mixed
     */
    public function find()
    {
        // TODO: Implement find() method.
    }

    /**
     * @return \mysqli
     * @throws \Exception
     */
    public function getConnect()
    {
        // TODO: Implement getConnect() method.
        //检查深度
        Helper::CheckPool(self::$_conn);
        //复用链接
        if (!self::$_conn[$this->_link]) {
            $conf = Config::get($this->_link);
            $conn = mysqli_connect($conf['host'], $conf['user'], $conf['password'], $conf['dbname'], $conf['port']);
            mysqli_set_charset($conn, isset($conf['charset'])?:'utf8');
            if (!$conn) {
                throw new \Exception(mysqli_connect_error());
            }
            self::$_conn[$this->_link] = $conn;
        }
        return self::$_conn[$this->_link];
    }
}