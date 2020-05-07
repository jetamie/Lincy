<?php


namespace library\Orm;


use MongoDB\Driver\Manager;

class Mongo7 implements Db
{
    /**
     * @var Manager
     */
    private $_link = null;

    private $_bulk;

    private $_writeConcern;

    private $_dbname;

    private $_collection;

    private $_where = [];

    private $_sort = [];

    private $_field = [];

    private $_options = [];

    public function __construct($link)
    {
        if (empty($link)) {
            throw new \Exception('链接为空!');
        }
        $this->_link = $link;
        $this->_bulk = new \MongoDB\Driver\BulkWrite();
        $this->_writeConcern   = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 100);
    }
    /**
     * 原生查询
     * @param $sql
     * @return mixed
     */
    public function query($sql)
    {
        // TODO: Implement query() method.
        return $this;
    }

    /**
     * 设置表名['user']
     * @param $table
     * @return mixed
     */
    public function table($table)
    {
        // TODO: Implement table() method.
        // TODO: Implement table() method.
        list($dbname, $collection) = explode('-', $table);
        if (empty($dbname) || empty($collection)) {
            return false;
        }
        $this->_dbname = $dbname;
        $this->_collection = $collection;
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
        if ($limit) {
            $this->_options['limit'] = $limit;
        }
        return $this;
    }

    public function skip($skip = 0)
    {
        // TODO: Implement limit() method.
        if ($skip) {
            $this->_options['skip'] = $skip;
        }
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
        if ($sort) {
            $this->_options['sort'] = $sort;
        }
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
    }

    /**
     * 更新数据
     * @param $data
     * @return mixed
     */
    public function update($data)
    {
        // TODO: Implement update() method.
        if ($this->_where) {
            $this->_bulk->update($this->_where, ['$set' => $data], ['multi' => true, 'upsert' => false]);
            $result = $this->_link->executeBulkWrite($this->_dbname . $this->_collection, $this->_bulk, $this->_writeConcern);
            return $result->getModifiedCount();
        } else {
            $result = $this->_bulk->insert($data);
            return $result->getInsertedCount();
        }
    }

    /**
     * 删除数据
     * @return mixed
     */
    public function delete()
    {
        // TODO: Implement delete() method.
        $result = $this->_bulk->delete($this->_where,$this->_options);
        return $result;

    }

    /**
     * 查询数据
     * @return mixed
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function find()
    {
        // TODO: Implement find() method.
        $query = new \MongoDB\Driver\Query($this->_where,$this->_options);
        $result = $this->_link->executeQuery($this->_dbname.$this->_collection, $query);
        $data = [];
        if ($result) {
            foreach ($result as $key => $value) {
                array_push($data, $value);
            }
        }

        return $data;
    }

    public function getConnect()
    {
        // TODO: Implement getConnect() method.
        return $this->_link;
    }

    public function close()
    {
        // TODO: Implement close() method.
        $this->_link = null;
    }
}