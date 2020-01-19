<?php
namespace app\business\Controller;

use library\Core\Controller;

class indexController extends Controller
{
    public function index()
    {
        return $this->_params;
    }

    public function test()
    {
        $s = $this->initSmarty();
        $s->assign('test','test');
        try {
            $s->display('test.tpl');
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
}