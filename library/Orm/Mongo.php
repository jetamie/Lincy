<?php


namespace library\Orm;


class Mongo implements Db
{
    /**
     * @var \MongoClient
     */
    private $_link = null;

    private $_connect = null;

    private $_where = [];

    private $_limit = 0;

    private $_skip = 0;

    private $_sort = [];

    private $_field = [];

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
        return false;
    }

    /**
     * 设置表名['u-user']
     * @param $table
     * @return mixed
     * @throws \Exception
     */
    public function table($table)
    {
        // TODO: Implement table() method.
        list($dbname, $collection) = explode('-', $table);
        if (empty($dbname) || empty($collection)) {
            return false;
        }
        $this->_connect = $this->_link->selectDB($dbname)->selectCollection($collection);
        return $this;
    }

    /**
     * 查询条件['id'=>1]
     * @param array $where
     * @return mixed
     */
    public function where($where = [])
    {
        // TODO: Implement where() method.
        $this->_where = $where;
        return $this;
    }

    /**
     * 限制条数
     * @param int $limit
     * @return mixed
     */
    public function limit($limit = 0)
    {
        // TODO: Implement limit() method.
        $this->_limit = $limit;
        return $this;
    }

    public function skip($skip = 0)
    {
        // TODO: Implement limit() method.
        $this->_skip = $skip;
        return $this;
    }

    /**
     * 排序['time'=>'desc|asc']
     * @param array $sort
     * @return mixed
     */
    public function sort($sort = [])
    {
        // TODO: Implement sort() method.
        $this->_sort = $sort;
        return $this;
    }

    /**
     * 设置查询字段['id','name']
     * @param array $field
     * @return mixed
     */
    public function field($field = [])
    {
        // TODO: Implement field() method.
        $this->_field = $field;
        return $this;
    }

    /**
     * 更新数据
     * @param $data array
     * @return mixed
     */
    public function update($data)
    {
        // TODO: Implement update() method.
        $res = $this->_connect->update($this->_where, $data);
        return $res;
    }

    /**
     * 删除数据
     * @return mixed
     */
    public function delete()
    {
        // TODO: Implement delete() method.
        $res = $this->_connect->remove($this->_where);
        return $res;
    }

    /**
     * 查询数据
     * @return mixed
     */
    public function find()
    {
        // TODO: Implement find() method.
        $cursor = $this->_connect->find($this->_where);
        if ($this->_sort) {
            $cursor->sort($this->_sort);
        }
        if ($this->_limit) {
            $cursor->limit($this->_limit);
        }
        if ($this->_skip) {
            $cursor->skip($this->_skip);
        }
        //循环读取每个匹配的文档
        $res = [];
        while($doc = $cursor->getNext()) {
            $res[] = $doc;
        }
        return $res;
    }

    public function getConnect()
    {
        // TODO: Implement getConnect() method.
        return $this->_link;
    }

    public function close()
    {
        // TODO: Implement close() method.
        $this->_link->close();
    }
}