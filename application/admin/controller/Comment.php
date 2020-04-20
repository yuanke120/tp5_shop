<?php
namespace app\admin\controller;

use think\Controller;

/**
 * Class Comment
 * @package app\admin\controller
 */
class Comment extends Controller
{
    public function index()
    {
        return "评论list";
    }

    public function feedback()
    {
        return "建议";
    }
}