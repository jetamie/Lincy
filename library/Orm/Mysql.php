<?php


namespace library\Orm;


class Mysql implements Db
{
    private $_link = null;

    private $_dbName = '';

    public function __construct($link, $dbName)
    {
        if (empty($link) || empty($dbName)) {
            throw new \Exception('链接或者数据库为空!');
        }
        $this->_link = $link;
        $this->_dbName = $dbName;
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
}