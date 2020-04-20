<?php
namespace app\admin\validate;

use think\Validate;

/**
 * Class Category
 * @package app\admin\validate
 */
class Category extends Validate
{
    /** 验证规则 **/
    protected $rule=[
      //  ['name','require|max:10','分类必须填写|分类不能超过10字'],
        'name'          =>'require|max:10',
        'parent_id'     =>'number',
        'id'            =>'number',
        //-1 删除 0 等待 1 正常
        'status'        =>'number|in:-1,0,1',
        'listorder'     =>'number',
    ];

    /** 提示验证失败的消息 **/
    protected $message=[
        'name.require'        => '分类必须填写',
        'name.max'            =>'分类不能超过10字',
        'status.number'      =>'状态1|0',
    ];

    /** 设置指定验证场景  某个验证场景需要验证这些验证**/
    protected $scene=[
        'add'       =>['name','prent_id','id'],
        'listorder' =>['id','listorder'], //排序
        'status'    =>['id','status'],
    ];
}