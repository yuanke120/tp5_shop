<?php
namespace app\admin\controller;

use think\Controller;

class Admin extends Controller
{
    public function  index()
    {
        return "管理列表";
    }
    public function  add()
    {
        return "管理添加";
    }
    public function  edit()
    {
        return "管理编辑";
    }
    public function del()
    {
        return "删除";
    }

    public function updatePassword()
    {
            return "更新密码";
    }
}