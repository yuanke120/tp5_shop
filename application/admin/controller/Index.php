<?php
namespace app\admin\controller;

use app\admin\common;
use think\Controller;

/**
 * Class Index
 * @package app\admin\controller
 */

class Index extends Controller{


    public function index()
    {
        return $this->fetch();
    }
    public function test()
    {
        //测试
       print_r(\Map::getLngLat('北京昌平沙河地铁'));

    }
    public function  map()
    {
        return \Map::staticImage('北京昌平沙河地铁');
    }

    public function welcome()
    {

      //  \phpmailer\Email::send('2162779282@qq.com','tp5','你去死把');
       // return "发送成功";
        return "欢迎o2o商城后台";
    }


}
