<?php
namespace app\bis\controller;

use think\Controller;
use app\bis\controller\Base as BaseController;
/**
 * Class Index
 * @package app\bis\controller
 */
class Index extends  BaseController
{
    public function index()
    {
        return $this->fetch();
    }
}
