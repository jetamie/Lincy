<?php


namespace library\Smarty;


use package\Config;

class Handle
{
    /**
     * @var \Smarty|null
     */
    private $_smarty = null;
    public $_flag = false;

    public function __construct()
    {
        if (!$this->_smarty) {
            $this->_smarty = new \Smarty();
        }
    }

    public function setDefault($template = '')
    {
        $conf = Config::get('smarty');
        //设置模板目录
        $this->_smarty->setTemplateDir($template);
        //这是编译目录
        $this->_smarty->setCompileDir($conf['compile_dir']);
        //左右分隔符
        $this->_smarty->setLeftDelimiter($conf['left_delimiter']);
        $this->_smarty->setRightDelimiter($conf['right_delimiter']);
        //Smarty输出的模板文件缓存的位置
        $this->_smarty->setCacheDir($conf['cache_dir']);
        //这里将以秒为单位进行计算缓存有效的时间。第一次缓存时间到期时当Smarty的caching变量设置为true时缓存将被重建。
        //当它的取值为-1时表示建立起的缓存从不过期，为0时表示在程序每次执行时缓存总是被重新建立
        $this->_smarty->setCacheLifetime($conf['CacheLifetime']);
        //0：Smarty默认值，表示不对模板进行缓存；
        //1：表示Smarty将使用当前定义的cache_lifetime来决定是否结束cache；
        //2：表示Smarty将使用在cache被建立时使用cache_lifetime这个值。习惯上使用true与false来表示是否进行缓存。
        $this->_smarty->setCaching($conf['caching']);
    }

    /**
     * @return \Smarty|null
     */
    public  function getSmarty()
    {
        return $this->_smarty;
    }
}