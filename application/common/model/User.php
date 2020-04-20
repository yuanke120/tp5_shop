<?php
namespace app\common\model;

use app\common\model\Base as BaseModel;

/**
 * Class User
 * @package app\common\model
 */
class User extends BaseModel
{
    /**
     * 根据用户名获取用户信息
     */
    public function  getUserByUserName($username)
    {
        if(!$username){
            exception('用户不合法');
        }
        $data=['username'=>$username];
        return $this->where($data)->find();
    }

    public function add($data=[])
    {
        //如果不是提交数据的数组
        if(!is_array($data)){
            exception('传递数据不是数组');
        }

        $data['status']=1;
        //过滤post数组的非数据表字段数据
        return $this->data($data)->allowField(true)->save();
    }
}