<?php


namespace library\Orm;


interface Db
{
    /**
     * 原生查询
     * @param $sql
     * @return mixed
     */
    public function query($sql);

    /**
     * 设置表名['user']
     * @param $table
     * @return mixed
     */
    public function table($table);

    /**
     * 查询条件['id'=>1]
     * @param array $where
     * @return mixed
     */
    public function where($where = []);

    /**
     * 限制条数
     * @param int $limit
     * @return mixed
     */
    public function limit($limit = 0);

    /**
     * 排序['time'=>'desc|asc']
     * @param array $sort
     * @return mixed
     */
    public function sort($sort = []);

    /**
     * 设置查询字段['id','name']
     * @param array $field
     * @return mixed
     */
    public function field($field = []);

    /**
     * 更新数据
     * @param $data
     * @return mixed
     */
    public function update($data);

    /**
     * 删除数据
     * @return mixed
     */
    public function delete();

    /**
     * 查询数据
     * @return mixed
     */
    public function find();
}