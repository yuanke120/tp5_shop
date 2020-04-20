<?php
namespace app\admin\controller;

use think\Controller;

/**
 * Class Base
 * @package app\admin\controller
 */
class Base extends  Controller
{
    public function status()
    {
        //获取值
        $data=input('get.');
        //validate 做个验证 id  status

        if(empty($data['id'])){
            $this->error('ID不合法');
        }
        if(!is_numeric($data['status'])){
            $this->success('状态不合法');
        }

        //获取控制器
        $model= request()->controller();
        //echo $model; exit;
        $result= model($model)->save(['status'=>$data['status']],['id'=>$data['id']]);
        if($result){
            $this->success('更新状态成功');
        }else{
            $this->error('更新状态失败');
        }
    }

    //排序功能 也能放在base
}