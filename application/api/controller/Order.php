<?php
namespace app\api\controller;

use think\Controller;

/**
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /**
     * @var \app\common\model\Order
     */
    private $order_model;

    public function _initialize()
    {
        parent::_initialize();
        $this->order_model=model('Order');
    }

    public function payStatus()
    {
        $id=input('post.id',0,'intval');
        if(!$id){
            return show(0,'error');
        }
        //判断登陆
       $order=$this->order_model->get($id);
        if($order->pay_status == 1){ //支付成功
            return show(1,'success');
        }
        return show(0,'error');

    }

}