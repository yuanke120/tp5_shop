<?php
namespace app\admin\controller;

use think\Controller;

class AuthRole extends  Controller
{
    public function index()
    {
        return "角色列表";
    }

    public function add()
    {
        return "添加";
    }

    public function edit()
    {
        return "编辑";
    }

    public function  del()
    {
        return "删除";
    }


}