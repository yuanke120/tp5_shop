<?php
namespace app\common\model;

use app\common\model\Base as BaseModel;

class Featured extends BaseModel
{
    /**
     * 根据类型获取列表数据
     * @param $type
     */
    public function getFeaturedByType($type)
    {
        $data = [
            'type'=>$type,
            'status'=>['neq',-1]
            ];
        $order=[
            'id'=>'desc',
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate(5);
        return $result;
    }
}