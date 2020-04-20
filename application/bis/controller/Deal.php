<?php
namespace app\bis\controller;

use app\bis\controller\Base as BaseController;
use app\common\model\BisLocation;
use app\common\model\Category;
use app\common\model\City;
use think\Request;

/**
 * Class Deal
 * @package app\bis\controller
 */
class Deal extends BaseController
{
    /**
     * @var City
     */
    private $bis_city_model;

    /**
     * @var Category
     */
    private $bis_category_model;

    /**
     * @var BisLocation
     */
    private $bis_location_model;

    /**
     * @var \app\common\model\Deal
     */
    private $bis_deal_model;

    public function _initialize()
    {
       parent::_initialize();
       $this->bis_city_model=model('City');
       $this->bis_category_model=model('Category');
       $this->bis_location_model=model('BisLocation');
       $this->bis_deal_model=model('Deal');
    }

    /**
     * 团购商户列表
     * @return mixed
     */
    public function index()
    {
        $dealList=$this->bis_deal_model->getDealByStatus();
        return $this->fetch('',[
            'deal_list'=>$dealList,
        ]);
    }

    /**
     * 团购商户新增
     * @param Request $request
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function add(Request $request)
    {
        $bisId = $this->getLoginUser()->bis_id;
        if ($request->isPost()) {
            //插入逻辑
            $data = input('post.');
            $location=model('BisLocation')->get($data['location_ids'][0]);
            //严格验证提交数据,validate自动

            $deal = [
                'bis_id' => $bisId,
                'name' => $data['name'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'se_category_id' => empty($data['se_category_id']) ? '' : implode(',', $data['se_category_id']),
                'city_id' => $data['city_id'],
                'location_ids' => empty($data['location_ids']) ? '' : implode(',', $data['location_ids']),
                'start_time' => strtotime($data['start_time']),
                'end_time' => strtotime($data['end_time']),
                'total_count' => $data['total_count'],
                'origin_price' => $data['origin_price'],
                'current_price' => $data['current_price'],
                'coupons_begin_time' => strtotime($data['coupons_begin_time']),
                'coupons_end_time' => strtotime($data['coupons_end_time']),
                'description' => $data['description'],
                'notes' => $data['notes'],
                'bis_account_id' => $this->getLoginUser()->id,
                'xpoint' => $location->xpoint,
                'ypoint' => $location->ypoint,

            ];
            $DealId = $this->bis_deal_model->add($deal);

            if ($DealId) {
                $this->success('团购添加成功', url('deal/index'));
            } else {
                $this->error('团购添加失败');
            }
        } else {
            //获取一级城市的数据
            $city = model('City')->getNormalCityByParentId();
            //获取一级分类的数据
            $category = model('Category')->getNormalCategoryByParentId();
            //获取session自己的账号的一级分类
            $location = $this->bis_location_model->getNormalLocationByBisId($bisId);

            return $this->fetch('', [
                'city' => $city,
                'category_list' => $category,
                'location_list' => $location,
            ]);
        }
    }

}

