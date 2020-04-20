<?php
namespace  app\common\model;

use app\common\model\Base as BaseModel;

class Deal extends BaseModel
{
    /**
     * @param array $data
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getNormalDeals($data=[])
    {
        $data['status']=1;
        $order=[
            'id'=>'desc',
        ];
        $result=$this->where($data)
            ->order($order)
            ->paginate(5);
      //  echo $this->getLastSql();
        return $result;
    }

    public function getApplyDeals($data=[])
    {
        $status['status']=0;
        $order=[
            'id'=>'desc',
        ];
        $result=$this->where($status)
            ->order($order)
            ->paginate();
        return $result;
    }

    public function getDealByStatus($status=1)
    {
        $status=[
            'status'=>$status
        ];
        $order=[
            'id'=>'desc',
        ];
        $result=$this->where($status)
            ->order($order)
            ->paginate(4);
        return $result;
    }

    /**
     * 根据分类 以及 城市来获取 商品数据
     * @param $id
     * @param $cityId
     * @param int $limit
     */
    public function getNormalDealByCategoryCityId($id, $cityId, $limit=10) {
        $data  = [
            'end_time' => ['gt', time()],
            'category_id' => $id,
            'city_id' => $cityId,
            'status' => 1,
        ];

        $order = [
            'listorder'=>'desc',
            'id'=>'desc',
        ];

        $result = $this->where($data)
            ->order($order);
        if($limit) {
            $result = $result->limit($limit);
        }
        return $result->select();
    }


    /**
     * 排序逻辑 一定要理解
     * @param array $data
     * @param $orders
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function  getDealByConditions($data=[],$orders)
    {
        if(!empty($orders['order_sales'])){
            $order['buy_count']='desc';
        }
        if(!empty($orders['order_price'])){
            $order['current_price']='desc';
        }
        if(!empty($orders['order_time'])){
            $order['create_time']='desc';
        }
        $order['id']='desc';

        $datas[] = 'end_time>'.time();
        $datas[] = 'status =1 ';

        if(!empty($data['se_category_id'])){
            $datas[]="find_in_set(".$data['se_category_id'].",se_category_id)";
        }
        if(!empty($data['category_id'])){
            $datas[]="category_id = ".$data['category_id'];
        }
        if(!empty($data['city_id'])){
            $datas[]="city_id = ".$data['city_id'];
        }

        $result= $this->where(implode(' AND ',$datas))
            ->order($order)
            ->paginate();
        return $result;
    }
    //支付成功 更新购买数量 setInc 自增
    public function updateBuyCountById($id,$buyCount)
    {
        //return $this->allowField(true)->where(['id'=>$id])->setInc('buy_count',$count);
        return $this->where(['id'=>$id])->setInc('buy_count',$buyCount);

    }

    //支付成功 更新商品数量
    public function  updateTotalCountById($id,$count)
    {

    }



}