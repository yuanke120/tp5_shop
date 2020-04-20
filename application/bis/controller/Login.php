<?php

namespace app\bis\controller;

use app\common\model\BisAccount;
use think\Controller;
use think\Session;

/**
 * Class Login
 * @package app\bis\controller
 */
class Login extends  Controller
{
    /**
     * 登陆查询
     * @return mixed|void
     * @throws \think\exception\DbException
     */
    public function index()
    {
        if(request()->isPost()){
            //获取相关的数据
            $data=input('post.');

            $result=model('BisAccount')->get(['username'=>$data['username']]);

            if(!$result || $result->status !=1){
                $this->error('用户不存在，获取用户未被审核通过');
            }
            if($result->password !=md5($data['password'].$result->code)){
                $this->error('密码不正确');
            }

            model('bisAccount')->updateById(['last_login_time'=>time()],$result->id);
            //保存用户信息， bis是作用域  ( 赋值bis作用域
            session('bisAccount',$result,'bis');
            //获取全部数据
            session('bisAccount',$result->getData());

            return $this->success('登陆成功',url('index/index'));
        }else{
            //获取session  (// 取值bis作用域
            $account = session('bisAccount','','bis'); //无需登陆 直接进入后台 因为session会话目前还在
            if($account && $account->id){
                return $this->redirect(url('index/index'));
            }
            return $this->fetch();
        }
    }

    /**
     * 退出登陆 session存储在服务端区块
     */
    public function logout()
    {
        session(null,'bis');
        $this->redirect('login/index');
       // $this->success('注销登陆成功',url('login/index'));
    }

}