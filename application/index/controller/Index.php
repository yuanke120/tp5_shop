<?php
namespace app\index\controller;

use app\common\model\Category;
use app\common\model\Deal;
use app\common\model\Featured;
use think\Controller;
use app\index\controller\Base as BaseController;

class Index extends BaseController
{


    public function index()
    {
        //获取首页大图 相关数据
        //显示推荐位信息
        //    return [1,2];
        $type0 = model('Featured')->where(['type'=>0,'status'=>1])->select(); // stype字段获取0
        $type1 = model('Featured')->where(['type'=>1,'status'=>1])->select(); // stype字段获取1
        //获取广告相关的数据
        //商品分类 数据-养生保健 推荐的数据
        $datas= model('Deal')->getNormalDealByCategoryCityId(1,$this->city->id);
        //获取4个子类
        $meishicates=model('Category')->getNormalRecommendCategoryByParentId(1,4);
        return $this->fetch('',[
            'datas'=>$datas,
            'meishicates'=>$meishicates,
            'controller'=>'ms',
            'type0'=>$type0,
            'type1'=>$type1,
        ]);
    }

}
