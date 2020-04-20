<?php
namespace app\index\controller;

use app\common\model\Category;
use app\common\model\Deal;
use think\Controller;
use app\index\controller\Base as BaseController;

/**
 * Class Lists
 * @package app\idnex\controller
 */
class Lists extends BaseController
{
    //        $this->view->count=$this->deal_model->count();
    /**
     * @var Category
     */
    private $bis_category_model;

    /**
     * @var Deal
     */
    private $deal_model;

    public function _initialize()
    {
        parent::_initialize();
        $this->bis_category_model=model('Category');
        $this->deal_model=model('Deal');

    }

    public function index()
    {
        $this->view->count=$this->deal_model->count();
        //思路 首先需要一级栏目
        $categorys = $this->bis_category_model->getNormalCategoryByParentId();
        foreach($categorys as $category){
            $firstCatIds[]=$category->id;
        }
        $id=input('id',0,'intval'); //intval获取整数值
        $data=[];
        //id=0 一级分类 二级分类  in_array() 找匹配
        if(in_array($id,$firstCatIds)){ //一级分类
            $categoryParentId = $id;
            //列表
            $data['category_id'] = $id;
        }elseif($id){ //二级分类
            //获取二级分类数据
            $category =model('Category')->get($id);
            if(!$category || $category->status !=1){
                $this->error('数据不合法');
            }
            $categoryParentId= $category->parent_id;
            $data['se_category_id']=$id;
        }else{ // 0
            $categoryParentId =0;

        }
        $sedcategorys=[];
        //获取父类的所有子分类
        if($categoryParentId){
            $sedcategorys = $this->bis_category_model->getNormalCategoryByParentId($categoryParentId);
        }
        $orders=[];
        //排序数据获取的逻辑
        $order_sales = input('order_sales','');
        $order_price = input('order_price','');
        $order_time  = input('order_time','');
        if(!empty($order_sales)){
            $orderflag = 'order_sales';
            $orders['order_sales'] = $order_sales;
        }elseif (!empty($order_price)){
            $orderflag='order_price';
            $orders['order_price'] = $orderflag;
        }elseif(!empty($order_time)){
            $orderflag = 'order_time';
            $orders['order_time'] = $order_time;
        }else{
            $orderflag ='';
        }

        //根据上面条件来查询商品列表数据
        //Log::write('o2o-log-list-id'.$id, 'log');

        trace('o2o-log-list-id'.$id,'log');

//        $data['city_id'] = $this->city->id; // add
        $deals=model('Deal')->getDealByConditions($data,$orders);
        return $this->fetch('',[
            'categorys'=>$categorys,
            'sedcategorys'=>$sedcategorys,
            'id'=>$id,
            'categoryParentId'=>$categoryParentId,
            'orderflag'=>$orderflag,
            'deals'=>$deals,

        ]);
    }
}