<?php
namespace app\admin\controller;

use app\common\model\City;
use think\Controller;
use app\admin\controller\Base as BaseController;

/**
 * Class Deal
 * @package app\admin\controller
 */
class Deal extends BaseController
{
    /**
     * @var City
     */
    private $bis_city_model;

    /**
     * @var \app\common\model\Category
     */
    private $bis_category_model;

    /**
     * @var \app\common\model\Deal
     */
     private $bis_deal_model;


    public function _initialize()
    {
        parent::_initialize();
        $this->bis_city_model=model('City');
        $this->bis_category_model=model('Category');
        $this->bis_deal_model=model('Deal');

    }

    public function index()
    {
        $data=input('get.');
        $datas=[];
        if(!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time']) > strtotime($data['start_time'])){
            $datas['create_time']=[
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])],
            ];
        }

        if(!empty($data['category_id'])){
            $datas['category_id'] = $data['category_id'];
        }

        if(!empty($data['city_id'])){
            $datas['city_id'] = $data['city_id'];
        }

        if(!empty($data['name'])){
            $datas['name']=['like','%'.$data['name'].'%'];
        }

        // 搜索
        $categoryId = empty($data['category_id']) ? '' : $data['category_id'];
        $cityId = empty($data['city_id']) ? '' : $data['city_id'];
        $startTime=empty($data['start_time']) ? '' : $data['start_time'];
        $endTime=empty($data['end_time']) ? '':$data['end_time'];
        $name=empty($data['name']) ? '' : $data['end_time'];

        $cityArrs = $categoryArrs = [];

        //获取一级分类的数据
        $categorys = $this->bis_category_model->getNormalCategoryByParentId();
        foreach($categorys as $category){
            $categoryArrs[$category->id] = $category->name;
        }

        //二级城市
        $citys = $this->bis_city_model->getNormalCitys();
        foreach($citys as $city) {
            $cityArrs[$city->id] = $city->name;
        }

        $dealList = $this->bis_deal_model->getNormalDeals($datas);
        return $this->fetch('',[
            'category_list'=>$categorys,
            'city'=>$citys,
            'deal_list'=>$dealList,
            'category_id'=>$categoryId,
            'city_id'=>$cityId,
            'start_time'=>$startTime,
            'end_time'=>$endTime,
            'name'=>$name,
            'categoryArrs'=>$categoryArrs,
            'cityArrs'=>$cityArrs,
        ]);
    }

    public function apply()
    {

        return $this->fetch();
    }
}