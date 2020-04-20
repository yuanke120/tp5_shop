<?php
namespace app\common\model;

use think\Model;
use app\common\model\Base as BaseModel;

/**
 * Class BisAccount
 * @package app\common\model
 */
class BisAccount extends BaseModel
{
    public function updateById($data, $id)
    {
        //allowField 过滤data数组中非数据中的数据
        return $this->allowField(true)->save($data,['id'=>$id]);
    }

    public function getLocationById($BisId)
    {
        $result=db('bis_account')
            ->alias('a')
            ->where('a.username',$BisId['username'])
            ->join('bis b','a.bis_id=b.id')
            ->join('bis_location c','c.bis_id=b.id')
           // ->where('c.status','1')
            ->field('c.*')
            ->paginate();
        return $result;
    }

}
