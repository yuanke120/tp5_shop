<?php
namespace app\admin\controller;

use phpmailer\Email;
use think\Controller;

/**
 * Class Bis
 * @package app\admin\controller
 */
class Bis extends Controller
{

    /**
     * @var \app\common\model\Bis
     */
    private $bis_model;

    public function _initialize()
    {
        parent::_initialize();
        $this->bis_model=model('Bis');
    }

    public function index()
    {
        $bisList= $this->bis_model->getBisByStatus(1); //显示status 1为启用
        return $this->fetch( '',['bis'=>$bisList]);
    }


    /**
     * 入驻申请列表
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function apply()
    {
        $bis = $this->bis_model->getBisByStatus(0);
        //$bis =model('BisLocation')->getBisLocationByStatus
        return $this->fetch('',[
            'bis'=>$bis
        ]);
    }

    /**
     * 商户详情
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function detail()
    {
        $id=input('get.id');
        if(empty($id)){
            return $this->error('ID错误');
        }
        /**
         * select选项一级分级二级
         */
        //获取一级城市的数据
        $city=model('City')->getNormalCityByParentId();
        //获取一级栏目的数据
        $category =model('Category')->getNormalCategoryByParentId();
        //获取商户数据
        $bisData=model('Bis')->get($id);
        $locationData=model('BisLocation')->get(['bis_id'=>$id,'is_main'=>1]);
        $accountData=model('BisAccount')->get(['bis_id'=>$id,'is_main'=>1]);
        return $this->fetch('',[
            'city' =>$city,
            'category_list'=>$category,
            'bisData'=>$bisData,
            'locationData'=>$locationData,
            'accountData'=>$accountData,
        ]);
    }

    /**
     * 修改状态
     */
    public function status()
    {
        $data = input('get.');
        $result = $this->bis_model->save(['status'=>$data['status']],['id'=>$data['id']]);
        $location = model('BisLocation')->save(['status'=>$data['status']],['bis_id'=>$data['id'],'is_main'=>1]);
        $account = model('BisAccount')->save(['status'=>$data['status']],['bis_id'=>$data['id'],'is_main'=>1]);
        if($result && $location && $account){
            //发送邮件
            //status 1  启用 2 不通过 -1 删除
//            \phpmailer\Email::send($data['email'],$title,$content);
            $this->success('状态更新成功');
        }else{
            $this->error('状态更新失败');
        }
    }

    public function del()
    {
        $this->fetch();
    }
}