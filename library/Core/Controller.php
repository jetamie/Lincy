<?php
namespace library\Core;

abstract class Controller
{
    /**
     * Post
     * @var Model
     */
    protected $_model;
    /**
     * Get
     * @var array
     */
    protected $_params = [];


    /**
     * 初始化
     * @param $params
     */
    public function init($params)
    {
        $this->_params = $params;
    }

    /**
     * @return Model
     */
    public function initModel()
    {
        $model = str_replace("Controller","Model", static::class);
        $model = '\\app\\business\\Controller\\'.$model;
        if (!$this->_model) {
            if (class_exists($model)) {
                $this->_model = new $model();
            }
        }
        return $this->_model;
    }
}