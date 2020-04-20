<?php
namespace app\common\model;


use think\Model;
use app\common\model\Base as BaseModel;


/**
 * Class Order
 * @package app\common\model
 */
class Order extends  BaseModel
{
    protected $autoWriteTimestamp=true;

    /**
     * 插入
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $data['status']=1;
        $this->save($data);
        return $this->id;

    }

    public function updateOrderByOutTradeNo($out_trade_no,$weixinData)
    {
        $data=[];
        if(!empty($weixinData['transaction_id'])){
            $data['transaction_id']=$weixinData['transaction_id'];
        }

        if(!empty($weixinData['total_fee'])){
            $data['total_fee']=$weixinData['total_fee'] / 100;
            $data['pay_status']=1;
           // $data['pay_time']=date('Y-m-d H:i:s',strtotime($weixinData['time_end']));
        }
        if (!empty($weixinData['time_end'])){
            $data['pay_time'] = $weixinData['time_end'];
        }
        return $this->allowField(true)->save($data,['out_trade_no'=>$out_trade_no]);
    }
}