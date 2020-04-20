<?php
namespace app\common\model;

use think\Model;
use app\common\model\Base as BaseModel;

/**
 * Class BisLocation
 * @package app\common\model
 */
class BisLocation extends BaseModel
{
    public function getNormalLocationByBisId($bisId)
    {
        $data=[
            'bis_id'=>$bisId,
            'status'=>1,
        ];
        $order=[
            'id'=>'desc'
        ];
        $result= $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }


    public function getNormalLocationId($ids)
    {
        $data=[
            'id'=>['in',$ids],
            'status'=>1,
        ];
        $result = $this->where($data)
                    ->select();
        return $result;
    }

    public function getLocationById($BisId)
    {
        $result=db('bis_account')
            ->alias('a')
            ->where('a.username',$BisId['username'])
            ->join('bis b','a.bis_id=b.id')
            ->join('bis_location c','c.bis_id=b.id')
            ->field('c.*')
            ->paginate();
        return $result;
    }

    /**
     *通过状态审核状态为0获取商家数据  0等待   1通过 2 删除
     * @param  $status
     */
    public function getBisLocationByStatus($status=0)
    {
        $status = [
            'status'=>$status,
        ];
        $order=[
            'id'=>'desc',
        ];
        $result = $this->where($status)
            ->order($order)
            ->paginate();
        return $result;
    }

}
