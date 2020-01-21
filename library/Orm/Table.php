<?php


namespace library\Orm;


class Table
{
    /**
     * @var Db|null
     */
    private  $_db = null;

    public function __construct(Db $db)
    {
        $this->_db = $db;
    }

    /**
     * 获取连接对象
     * @return Db|null
     */
    public function getAdapter()
    {
        return $this->_db;
    }
}