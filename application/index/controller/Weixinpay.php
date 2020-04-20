<?php
namespace app\index\controller;

use think\Controller;
use wxpay\database\WxPayResults;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\NativePay;
use wxpay\WxPayConfig;
use wxpay\WxPayApi;
use wxpay\WxPayNotify;
use wxpay\PayNotifyCallBack;
use wxpay\database\WxPayDataBase;

class Weixinpay extends  Controller
{
    public function notify()
    {
        $weixinData = file_get_contents("php://input");
        file_put_contents('/tmp/4.txt', $weixinData, FILE_APPEND);

        try {
            $result=new WxPayResults();
            $weixinData=$result->Init($weixinData);
        }catch (\Exception $e){
            $result->setData('return_code','FAIL');
            $result->setData('return_msg',$e->getMessage());
            return $result->toXml();
            //echo $e->getMessage();exit;
        }
        if($weixinData['return_code'] === 'FAIL' || $weixinData['result_code'] !== 'SUCCESS'){
            $result->setData('return_code','FAIL');
            $result->setData('return_msg','error');
            return $result->toXml();

        }
        //根据out_trade_to来查询订单数据
        $outTradeTo=$weixinData['out_trade_no'];
        $order=model('Order')->get(['out_trade_to'=>$outTradeTo]);
        if(!$order || $order->pay_status == 1){
            $result->setData('return_code','SUCCESS');
            $result->setData('return_msg','OK');
            return $result->toXml();
        }

        //更新表 订单表  商品表
        try{
            $resultRes=model('Order')->updateOrderByOutTradeNo($outTradeTo,$weixinData);
            model('Deal')->updateBuyCountById($order->deal_id,$order->deal_count);
            //消费生成
            $coupons=[
                'sn'=>$outTradeTo,
                'password'=>rand(1000,10000),
                'user_id'=>$order->user_id,
                'deal_id'=>$order->deal_id,
                'order_id'=>$order->id,
            ];
            model('Coupons')->add($coupons);

            //发送邮件 给用户
        }catch (\Exception $e){
            //说明没更新 告诉微信服务器 我还需要回调
            return false;
        }
        $result->setData('return_code','SUCCESS');
        $result->setData('return_msg','OK');
        return $result->toXml();
    }

    public function wxpayQCode($id)
    {
        $notify=new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody("支付 0.01元");
        $input->SetAttach("支付 0.01元");
        $input->SetOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotalFee("1");
        $input->SetTimeStart(date("YmdHis"));
        $input->SetTimeExpire(date("YmdHis", time() + 600));
        $input->SetGoodsTag("test");
        $input->SetNotifyUrl("/index.php/index/weixinpay/notify");
        $input->SetTradeType("NATIVE");
        $input->SetProductId($id);
        $result = $notify->getPayUrl($input);
        if(empty($result['code_url'])){
            $url='';
        }else{
            $url= $result["code_url"];

        }
        return'	<img alt="模式二扫码支付" src="/weixin/example/qrcode.php?data=<?php echo urlencode($url);?>" style="width:150px;height:150px;"/>';
    }

}