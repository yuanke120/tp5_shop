<?php
namespace app\common\model;

use think\Model;

/**
 * Class City
 * @package app\common\model
 */
class City extends Model
{
    public function getNormalCityByParentId($parentId=0)
    {
        $data=[
            'status'        =>1,
            'parent_id'     =>$parentId,
        ];

        $order=[
            'id'=>'desc',
        ];
        $result = $this->where($data)
            ->order($order)
            ->select();
        return $result;
    }

    public function getNormalCitys()
    {
        $data=[
            'status'=>1,
            'parent_id'=>['gt',0]
        ];
        $order=[
            'id'=>'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }

}