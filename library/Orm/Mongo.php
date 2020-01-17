<?php


namespace library\Orm;


class Mongo
{
    protected $_cate = null;

    protected $_table = null;

    protected $_order = null;

    protected $_limit = null;

    protected $_skip = null;

    protected $_fields = null;

    protected $_sort = null;

    protected $_up = null;

    protected $_instance = null;

    protected $_version = null;

    protected $_charset = null;

    public function __construct()
    {
        $version = explode('.', PHP_VERSION);
        $this->_version = $version[0];
    }

    public function setTable($cate, $table)
    {
        $this->_cate = $cate;
        $this->_table = $table;
        return $this;
    }

    public function skip($skip)
    {
        if ($skip)
            $this->_skip = $skip;
        return $this;
    }

    /**
     * 设置条数限制
     * @param int $num
     * @return $this
     */
    public function limit($num)
    {
        if ($num)
            $this->_limit = $num;
        return $this;
    }

    /**
     * @param $charset
     * @return $this
     */
    public function charset($charset)
    {
        if ($charset)
            $this->_charset = strtolower($charset);
        return $this;
    }
    public function toUpper()
    {
        $this->_up = true;
        return $this;
    }
    /**
     * 设置排序规则
     * @param array $sort
     * array('a'=>-1,'b'=>1) -1降序，1升序
     * @return $this
     */
    public function sort($sort)
    {
        if ($sort)
            $this->_sort = $sort;
        return $this;
    }

    /**
     * 设置过滤字段
     * @param $fields ['a','b']
     * @return $this
     */
    public function field($fields)
    {
        if ($fields)
            $this->_fields = $fields;
        return $this;
    }

    /**
     * 操作对象
     * @param $cate
     * @param $table
     * @return bool
     */
    private function _getSaveTable($cate, $table)
    {
        try {
            if (!$this->_instance && $this->_version = 7) {
                $mongo = new \MongoDB();
                $service = $mongo->randService();
                $this->_instance = $mongo->getConnect($service['ip'], $service['port']);
            } else {
                $mongo = new \MongoClient('mongodb://localhost:27017');
            }
            return  $mongo->$cate->$table;
        } catch (\Exception $e) {

        }
        return false;
    }

    public function save($data)
    {
        $mongo = $this->_getSaveTable($this->_cate, $this->_table);
        return $mongo->save($data);
    }

    public function remove($where)
    {
        $mongo = $this->_getSaveTable($this->_cate, $this->_table);
        return $mongo->remove($where);
    }

    public function find($query=array())
    {
        $mongo = $this->_getSaveTable($this->_cate, $this->_table);
        switch ($this->_version) {
            case 7:
                $options = array();
                if ($this->_sort) {
                    $options['sort'] = $this->_sort;
                }
                if ($this->_skip) {
                    $options['skip'] = $this->_skip;
                }
                if ($this->_limit) {
                    $options['limit'] = $this->_limit;
                    $options['batchSize'] = $this->_limit;
                }
                if ($this->_fields) {
                    foreach ($this->_fields as $key => $value) {
                        $newFields[$value] = 1;
                    }
                    $options['projection'] = $newFields;
                }
                if ($options) {
                    $cursor = $mongo->find($query, '', $options);
                } else {
                    $cursor = $mongo->find($query);
                }
                break;
            default:
                if ($this->_fields) {
                    $cursor = $mongo->find($query, $this->_fields);
                } else {
                    $cursor = $mongo->find($query);
                }
                if ($this->_sort) {
                    $cursor->sort($this->_sort);
                }
                if ($this->_skip) {
                    $cursor->skip($this->_skip);
                }
                if ($this->_limit) {
                    $cursor->limit($this->_limit);
                    $cursor->batchSize($this->_limit);
                }
                break;
        }
        $result = iterator_to_array($cursor);
        $this->setNull();
        $tmp = $result;
        if ($this->_charset &&  $this->_charset == 'utf-8') {

        } else {
            $result = eval('return '. iconv("UTF-8", "GBK//IGNORE", var_export($tmp, true).';'));
            if (!$result) {
                $result = eval('return '. iconv("UTF-8", "GBK//TRANSLIT", var_export($tmp, true).';'));
            }
        }
        if ($this->_up) {
            $result = $this->strtoupper($result);
        }
        return array_values($result);
    }

    public function strtoupper($data)
    {
        $result =array();
        foreach ($data as $id=>$value) {
            foreach ($value as $key=>$val) {
                $result[$id][strtoupper($key)] = $val;
            }
        }
        return $result;
    }

    public function setNull()
    {
        $this->_order = null;
        $this->_limit = null;
        $this->_fields = null;
        $this->_sort = null;
    }
}