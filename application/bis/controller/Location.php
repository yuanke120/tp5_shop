<?php
namespace app\bis\controller;

use app\bis\controller\Base as BaseController;
use app\common\model\Bis;
use app\common\model\BisAccount;
use app\common\model\BisLocation;
use think\Session;


/**
 * Class Location
 * @package app\bis\controller
 */
class Location extends  BaseController
{
    /**
     * @var Bis
     */
    private $bis_model;

    /**
     * @var BisAccount
     */
    private $bis_account_model;

    /**
     * @var BisLocation
     */
    private $bis_location_model;

    public function _initialize()
    {
         parent::_initialize();
         $this->bis_model=model('Bis');
         $this->bis_account_model=model('BisAccount');
         $this->bis_location_model=model('BisLocation');
    }

    /**
     * 测试成功
     * 商户列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function index2()
    {
        $this->view->count=$this->bis_account_model->count();

        //先判断admin 用户 ==
        //先通过session获取用户登录取决于列表
        $account=session('bisAccount.username');

        if($account=='admin'){
            $locationList=$this->bis_account_model->all();
        }else{
            $locationList=$this->bis_account_model->all(['username'=>$account]);
        }
        return $this->fetch('',['location_list'=>$locationList]);
    }

    /**
     * 商户列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->view->count=$this->bis_account_model->count();

        $account=session('bisAccount.username');
        if($account == 'admin'){
            $locationList=$this->bis_account_model->all();
        }else{
            $locationList=$this->bis_account_model->getLocationById(['username'=>$account]);
        }
        return $this->fetch('',['location_list'=>$locationList]);
    }

    /**
     *商户新增
     * @return mixed
     */
    public function add()
    {
        if(request()->isPost()){
            $data=input('post.');
            //获取门店归属的商户id
            $bisId=$this->getLoginUser()->bis_id;
            $data['cat']='';
            if(!empty($data['se_category_id'])){
                $data['cat']=implode("|",$data['se_category_id']);
            }

            //获取经纬度
            $lngLat = \Map::getLngLat($data['address']);
            if(empty($lngLat) || $lngLat['status'] !=0 || $lngLat['result']['precise'] !=1) {
                $this->error('无法获取数据，或者匹配的地址不精确');
            }

            // 门店入库操作
            //总店相关信息入库
            $locationData=[
                'bis_id'=>$bisId,
                'name'=>$data['name'],
                'logo'=>$data['logo'],
                'tel'=>$data['tel'],
                'contact'=>$data['contact'],
                'category_id'=>$data['category_id'],
                'category_path'=>$data['category_id'].','.$data['cat'],
                'city_id'=>$data['city_id'],
                'city_path'=>empty($data['se_city_id'])  ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
                'address' => $data['address'],
                'is_main' => 1,// 代表的是总店信息
                'api_address' => $data['address'],
                'bank_info'=>$data['bank_info'],
                'open_time'=>$data['open_time'],
                'content'=>empty($data['content']) ? '': $data['content'],
                'xpoint'=>empty($lngLat['result']['location']['lng']) ? '' : $lngLat['result']['location']['lng'],
                'ypoint'=>empty($lngLat['result']['location']['lat']) ? '' : $lngLat['result']['location']['lat'],
            ];
            $locationId = $this->bis_location_model->add($locationData);
            if($locationId){
                return $this->success('门店申请成功');
            }else{
                return $this->error('门店申请失败');
            }
        }else {
            //获取一级城市的数据
            $city = model('City')->getNormalCityByParentId();
            //获取一级分类的数据
            $category = model('Category')->getNormalCategoryByParentId();
            return $this->fetch('', [
                'city' => $city,
                'category_list' => $category,
            ]);
        }
    }

    /**
     * 下架状态(等待解决有问题)
     */
    /**
     * 修改状态
     */
    public function status()
    {
        $data = input('get.');
        $result = $this->bis_model->save(['status'=>$data['status']],['id'=>$data['id']]);
        $location = $this->bis_account_model->save(['status'=>$data['status']],['bis_id'=>$data['id'],'is_main'=>1]);
        if($result && $location){
            $this->success('状态更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }

}