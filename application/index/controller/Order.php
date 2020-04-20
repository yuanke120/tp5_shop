<?php
namespace app\index\controller;

use app\index\controller\Base as BaseController;

/**
 * Class Order
 * @package app\index\controller
 */
class Order extends BaseController
{
    public function index()
    {
        $user=$this->getLoginUser();
        if(!$user){
            $this->error('请您登陆','user/login');
        }
        $id=input('get.id',0,'intval');

        if(!$id){
            $this->error('参数不合法');
        }
        $dealCount=input('get.deal_count',0,'intval');
        $dealPrice=input('get.total_price');

        $deal=model('Deal')->find($id);
        if(!$deal || $deal->status != 1){
            $this->error('商品不存在');
        }

        if(empty($_SERVER['HTTP_REFERER'])){
            $this->error('请求不合法');
        }
        $orderSn=setOrderSn();
        //插入数据
        $data=[
            'out_trade_no'=>$orderSn,
            'user_id'=>$user->id,
            'username'=>$user->username,
            'deal_id'=>$id,
            'deal_count'=>$dealCount,
            'total_price'=>$dealPrice,
            'referer'=>$_SERVER['HTTP_REFERER'],
        ];
        try {
            $orderId=model('Order')->add($data);
        }catch (\Exception $e){
            $this->error('订单处理失败');
        }
            $this->redirect('pay/index',['id'=>$orderId]);
    }

    public function pay()
    {
        if(!$this->getLoginUser()){
            $this->error('请您登陆',url('user/login'));
        }
        $id=input('get.id',0,'intval');
        if(!$id){
            $this->error('参数不合法');
        }
        $count=input('get.count',1,'intval');
        $deal=model('Deal')->find($id);
//        echo $deal->getLastSql();exit;
        if(!$deal || $deal->status != 1){
            $this->error('商品不存在');
        }
        $deal=$deal->toArray();
//        dump($deal);exit;
        return $this->fetch('',[
            'controller'=>'pay',
            'count'=>$count,
            'deal'=>$deal,
        ]);
    }
}
