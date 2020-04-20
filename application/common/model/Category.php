<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
   public function add($data)
    {
        $data['status']=1;
        $data['create_time']=date('Y-m-d H:i',time());
        $data['update_time']=date('Y-m-d H:i:',time());
        //getlastsql() 调试执行的sql
//        echo $this->getLastSql(); exit;
        return $this->save($data);
    }

    public function getCategory()
    {
        $data=[
            'status'        =>1,
            'parent_id'     =>0,
        ];
        $order=[
            'id'  =>'desc'
        ];
       return  $this->where($data)
            ->order($order)
           ->select();
    }

    public function getCategorys($parent_id = 0)
    {
        $data=[
            'parent_id'    =>$parent_id,
            'status'       =>['neq',-1],
        ];

        $order=[
            'listorder'=>'desc',
            'id'    =>'desc',
        ];
        $result=$this->where($data)
            ->order($order)
            ->paginate(5);
            return $result; 
    }

    public function getNormalCategoryByParentId($parentId=0)
    {
        $data = [
            'status' => 1,
            'parent_id' => $parentId,
        ];
        $order = [
            'id' => 'desc',
        ];
        return $this->where($data)
            ->order($order)
            ->select();
    }

    public function getNormalRecommendCategoryByParentId($id=0,$limit=5)
    {
        $data=[
            'parent_id'=>$id,
            'status'=>1,
        ];

        $order=[
            'listorder'=>'desc',
            'id'=>'desc',
        ];
        $result = $this->where($data)
            ->order($order);
        if($limit){
            $result=$result->limit($limit);
        }
        return $result->select();
    }

    public function getNormalCategoryIdParentId($ids)
    {
        $data=[
            'parent_id'=>['in',implode(',',$ids)],
            'status'=>1
        ];
        $order=[
            'listorder'=>'desc',
            'id'=>'desc',

        ];
        $result= $this->where($data)
            ->order($order)->select();

        return $result;
    }

}