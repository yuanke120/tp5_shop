<?php
namespace app\api\controller;

use think\Controller;

/**
 * Class City
 * @package app\api\controller
 */
class City extends Controller
{
    /**
     * @var \app\common\model\City
     */
    private $city_model;

    public function _initialize()
    {
        parent::_initialize();
        $this->city_model=model("City");
    }

    public function getCityByParentId()
    {
        $id=input('post.id');
        if(!$id){
            $this->error('ID合法');
        }
        //halt($id);
        //通过id获取二级城市
        $city= $this->city_model->getNormalCityByParentId($id);
        if(!$city){
            return show(0,'error');
        }
            return show(1,'success',$city);
        }

}