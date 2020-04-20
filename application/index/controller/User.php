<?php
namespace app\index\controller;

use app\common\common;
use think\Controller;
use think\Exception;
use think\Request;

class User extends  Controller
{
    /**
     * @var \app\common\model\User
     */
    private $user_model;

    public function _initialize()
    {
        parent::_initialize();
        $this->user_model=model('User');
    }

    /**
     * 登陆界面
     * @return mixed
     */
    public function login()
    {
        //获取session
        $user=session('o2o_user','','o2o');
        if($user && $user->id){
            $this->redirect(url('index/index'));
        }
        return $this->fetch();
    }

    public function loginCheck(Request $request)
    {
        //判定
        if(!$request->isPost()){
            $this->error('提交不合法');
        }
        $data=input('post.');
        //严格验证 validate

        try {
            $user=$this->user_model->getUserByUserName($data['username']);
            //print_r($user);
        }catch (\Exception $exception){
            $this->error($exception->getMessage());
        }
//        print_r($user);
        if(!$user || $user->status != 1){
            $this->error('用户不存在');
        }
        //判断密码是否合理
        if(common::encrypt_password($data['password'].$user->code) != $user->password){
            $this->error('密码错误');
        }
        //登陆success
        $this->user_model->updateById(['last_login_time'=>time()],$user->id);

        session('o2o_user',$user,'o2o');

        $this->success('登陆成功',url('index/index'));
    }

    /**
     * 退出登陆
     */
    public function logout()
    {
        session(null,'o2o');
        $this->redirect(url('user/login'));
    }

    /**
     * 注册
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        if($request->isPost()){
            $data=input('post.');
            if(!captcha_check($data['verifycode'])){
                $this->error('验证码错误');
            }
            //严格校验validate
            if ($data['password'] != $data['repassword']){
                $this->error('两次密码输入不一样');
            }
            //自动生成 密码的加盐字符串
            $data['code'] = mt_rand(100, 10000);
            $data['password'] = md5($data['password'].$data['code']);
            try {
                $result = $this->user_model->add($data);
            }catch (\Exception $exception){
                $this->error($exception->getMessage());
            }
            if($result){
                $this->success('注册成功',url('user/login'));
            }else{
                $this->error('注册失败');
            }
        }else {
            return $this->fetch();
        }
    }
}