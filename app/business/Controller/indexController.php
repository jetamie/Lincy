<?php
namespace app\business\Controller;

use library\Core\Controller;

class indexController extends Controller
{
    public function index()
    {
        $this->assign('msg','Hello Lincy!');
        $this->assign('github','https://github.com/jetamie/Lincy');
        $this->display('index.tpl');
    }
    public function api()
    {
        return [
            'msg' => 'Hello Lincy!'
        ];
    }
}