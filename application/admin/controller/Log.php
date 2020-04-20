<?php
namespace app\admin\controller;

use think\Controller;

class Log extends Controller
{
    public function index()
    {
        return "登陆日志";
    }

    public function cation()
    {
        return "分类";
    }

    public function mysql()
    {
        return "查看所有数据库";
    }


}