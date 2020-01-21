<?php
namespace library\Core;

use library\Smarty\Handle as Smarty;

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
     * @var Smarty
     */
    protected $_smarty = null;


    /**
     * 初始化
     * @param $params
     */
    public function init($params)
    {
        $this->_params = $params;
        if (!$this->_smarty) {
            $this->_smarty = new Smarty();
        }
        $this->initSmarty();
    }

    /**
     * @return Model
     */
    public function initModel()
    {
        $model = str_replace('Controller','Model', static::class);
        $model = '\\app\\business\\Controller\\'.$model;
        if (!$this->_model) {
            if (class_exists($model)) {
                $this->_model = new $model();
            }
        }
        return $this->_model;
    }

    /**
     * 初始化Smarty
     */
    public function initSmarty()
    {
        $view = explode('\\', static::class);
        $len = count($view);
        $view[$len-2] = 'View';
        $view[$len-1] = str_replace('Controller', '', $view[$len-1]);
        $viewPath = APP.implode('\\', $view).'/';
        $this->_smarty->setDefault($viewPath);
    }

    /**
     * assign-Smarty
     * @param $tal_var
     * @param null $value
     * @param bool $nocache
     */
    public function assign($tal_var, $value = null, $nocache = false)
    {
        $this->_smarty->getSmarty()->assign($tal_var, $value, $nocache);
    }

    /**
     * display-Smarty
     * @param null $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        try {
            $this->_smarty->getSmarty()->display($template, $cache_id, $compile_id, $parent);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }
}