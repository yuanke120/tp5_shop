<?php
namespace app\admin\controller;

use think\Controller;

class User extends Controller
{
    public function  index()
    {
        return "会员列表";
    }

    public function del()
    {
        return "删除会员";
    }

    public function level()
    {
        return "会员等级";
    }
}