<?php
/**
 * logic基类
 */
namespace app\admin\common\logic;

use think\Model;

class BaseLogic extends Model
{
    public function initialize()
    {
        parent::initialize();
        if(!defined('ADD_SUCCESS_TXT')){define('ADD_SUCCESS_TXT','添加成功');};
        if(!defined('ADD_FAILURE_TXT')){define('ADD_FAILURE_TXT','添加失败');};
    }
}