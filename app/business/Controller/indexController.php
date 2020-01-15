<?php
namespace app\business\Controller;

use library\Core\Controller;

class indexController extends Controller
{
    public function index()
    {
        return $this->_params;
    }
}