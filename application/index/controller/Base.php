<?php
namespace app\index\controller;

use think\Controller;

/**
 * Class Base
 * @package app\index\controller
 */
class Base extends Controller
{
    public $city = '';
    public $account='';

    public function _initialize()
    {
        //城市数据
        parent::_initialize();
        //用户数据

        $citys=model('City')->getNormalCitys();

        $this->getCity($citys);
        $login=$this->getLoginUser();
        //获取首页分类的数据
        $cats = $this->getRecommendCats();

        $this->assign('citys',$citys);
        $this->assign('city', $this->city);
        $this->assign('cats',$cats);
        $this->assign('controller',strtolower(request()->controller()));
        $this->assign('user',$login);
        $this->assign('title','YK-2020');
    }

    //  城市分类
    public function getCity($citys)
    {
        foreach ($citys as $city){
            //toArray转换为数组
            $city = $city->toArray();
//            print_r($city);
            if($city['is_default'] == 1){
                $defaultuname=$city['uname'];
                break; // 终止foreach
            }
        }

        $defaultuname = $defaultuname ? $defaultuname : 'nanchang';
        if(session('cityuname','','o2o') && !input('get.city')){
            $cityuname = session('cityuname','','o2o');
        }else{
            $cityuname = input('get.city',$defaultuname,'trim');
            session('cityuname',$cityuname,'o2o');
        }

        $this->city = model('City')->where(['uname'=>$cityuname])->find();

    }

    /**
     * 登陆session
     * @return mixed
     */
    public function getLoginUser()
    {
        if(!$this->account){
            $this->account=session('o2o_user','','o2o');
        }
        return $this->account;
    }

    /**
     * 获取首页推荐当中的商品分类数据
     */
    public  function getRecommendCats()
    {
        $parentIds = $sedcatArr = $recomCats = [];
        $cats = model('Category')->getNormalRecommendCategoryByParentId(0,5);
        foreach($cats as $cat){
            $parentIds[] = $cat->id;
        }

        //获取二级分类的数据
        $sedCats = model('Category')->getNormalCategoryIdParentId($parentIds);
        foreach($sedCats as $sedCat){
            $sedcatArr[$sedCat->parent_id][]=[
                'id'=>$sedCat->id,
                'name'=>$sedCat->name,
            ];
        }

        foreach($cats as $cat){
            //recomCats 代表以及和二级数据 []第一个参数是一级分类的name 第二个参数是此以及分类下面的所有二级分类数据
            $recomCats[$cat->id] = [$cat->name,empty($sedcatArr[$cat->id]) ? [] : $sedcatArr[$cat->id]];
        }


        return $recomCats;
    }

}