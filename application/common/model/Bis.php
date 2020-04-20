<?php
namespace  app\common\model;

use think\Model;
use app\common\model\Base as BaseModel;

/**
 * Class Bis
 * @package app\commonm\model
 */
class Bis extends BaseModel
{
    /**
     *通过状态审核状态为0获取商家数据  0等待   1通过 2 删除
     * @param  $status
     */
    public function getBisByStatus($status=0)
    {
        $data = [
            'status'=>$status
        ];
        $order=[
            'id'=>'desc',
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }
}