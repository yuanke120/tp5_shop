<?php
namespace app\index\controller;

use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayConfig;
use wxpay\WxPayApi;
use wxpay\WxPayNotify;
use wxpay\PayNotifyCallBack;
use app\index\controller\Base as BaseController;

/**
 * Class Pay
 * @package app\index\controller
 */
class Pay extends BaseController
{
    public function index()
    {
        if(!$this->getLoginUser()){
            $this->error('请登陆','user/login');
        }
        $orderId=input('get.id',0,'intval');
        if (empty($orderId)){
            $this->error('请求不合法');
        }
        $order=model('Order')->get($orderId);
        if(empty($order) || $order->status != 1 || $order->pay_status !=0){
            $this->error('无法操作');
        }
        $deal=model('Deal')->get($order->deal_id);
        //生成二维码
        $notify=new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody($deal->name);
        $input->SetAttach($deal->name);
        $input->SetOutTradeNo($order->out_trade_no);
        $input->SetTotalFee($order->total_price*100);
        $input->SetTimeStart(date("YmdHis"));
        $input->SetTimeExpire(date("YmdHis", time() + 600));
        $input->SetGoodsTag("QRCode");
        $input->SetNotifyUrl("http://49.235.241.33/index.php/index/weixinpay/notify");
        $input->SetTradeType("NATIVE");
        $input->SetProductId($order->deal_id);
        $result = $notify->getPayUrl($input);
        if(empty($result['code_url'])){
            $url='';
        }else{
            $url= $result["code_url"];

        }
        return $this->fetch('',[
            'deal'=>$deal,
            'order'=>$order,
            'url'=>$url,
        ]);
    }

    public function paysuccess()
    {
        if(!$this->getLoginUser()){
            $this->error('请你登陆','user/login');
        }
        return $this->fetch();
    }
}