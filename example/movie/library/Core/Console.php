<?php
namespace library\Core;
/**
 * Interface Console
 * console接口
 */
abstract class Console
{
    protected $_param = [];
    /**
     * @var null|Model
     */
    protected $_model = null;
    /**
     * 默认方法
     * @return mixed
     */
    abstract public function index();

    /**
     * 数据接收器
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {

    }

    /**
     * 初始化函数
     * @param $argv
     */
    public function init($argv)
    {
        if (!empty($argv)) {
            $param = [];
            foreach ($argv as $item) {
                if (strpos($item, "=") === false) {
                    continue;
                }
                $tmp = explode("=", $item);
                $param[str_replace("-", "", $tmp[0])] = $tmp[1];
            }
            $this->_param = $param;
        }
    }

    /**
     * @return Model
     */
    public function initModel()
    {
        $model = explode('\\', static::class);
        $len = count($model);
        $model[$len-2] = 'Model';
        $model[$len-1] = str_replace('Console', 'Model', $model[$len-1]);
        $model = implode('\\', $model);
        if (!$this->_model) {
            if (class_exists($model)) {
                $this->_model = new $model();
            }
        }
        return $this->_model;
    }
}