<?php
namespace app\bis\controller;

use think\Controller;
use think\Session;

/**
 * Class Base
 * @package app\bis\controller
 */
class Base extends Controller
{
    public $account;

    public function _initialize()
    {
        //判定用户是否登陆
        $isLogin = $this->isLogin();
        if(!$isLogin){
            return $this->redirect(url('login/index'));
        }
    }


    //判定是否登陆
    public function  isLogin()
    {
        //获取session
        $user=$this->getLoginUser();
        if($user && $user->id){
            return true;
        }
        return false;
    }

    public function getLoginUser()
    {
       if(!$this->account){
           $this->account=session('bisAccount','','bis');
       }
       return $this->account;
    }

}