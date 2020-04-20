<?php
/**
 * 模型base公共基类model层
 */
namespace app\common\model;

use think\Model;

/**
 * Class Base
 * @package app\common\model
 */
class Base extends Model
{
    protected $autoWriteTimestamp=true;

    protected $dateFormat='Y-m-d H:i';

    public function add($data)
    {
        $data['status'] = 0;
        $this->save($data);
        return $this->id;
    }

    public function updateById($data,$id)
    {
        $result=$this->allowField(true)->save($data,['id'=>$id]);
        return $result;
    }


}