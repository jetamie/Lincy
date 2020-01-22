<?php
namespace app\business\Controller;

use library\Core\Controller;

class indexController extends Controller
{
    public function __construct()
    {
        $this->initModel();
    }

    public function index()
    {
        $this->assign('title','CY Movie');
        $this->display('index.tpl');
    }
    public function detail()
    {
        $this->assign('title','CY Movie detail');
        $this->display('detail.tpl');
    }
    public function player()
    {
        $this->assign('title','CY Movie player');
        $this->display('player.tpl');
    }

    public function search()
    {
        $data = $this->_model->search($this->_params);
    }

    public function api()
    {
        $data = $this->_model->getDetailList($this->_params);
        $result =  [
            'code' => 0,
            'msg' => 'ok',
            'data' => [
                'page' => $data[0],
                'list' => $data[1],
            ]
        ];
        if (empty($data[1])) {
            $result['code'] = -1;
            $result['msg'] = 'null';
        }
        return $result;
    }
}