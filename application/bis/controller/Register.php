<?php
namespace app\bis\controller;

use think\Controller;
use app\common\model\City;
use app\common\model\Bis;
use think\Model;
use think\Request;
use think\File;

/**
 * Class Register
 * @package app\bis\controller
 */
class Register extends Controller
{
    public function index()
    {
        /**
         * select选项一级分级二级
         */
        //获取一级城市的数据
        $city= model('City')->getNormalCityByParentId();
        //获取一级分类栏目的数据
        $category =model('Category')->getNormalCategoryByParentId();
         return $this->fetch('',[
             'city' =>$city,
             'category_list'=>$category,
         ]);
    }

    public function add()
    {
        if(!request()->isPost()){
            $this->error('请求错误');
        }
        //获取表单值
        $data=input('post.');

        //检验数据
        $validate= validate('Bis');
        if(!$validate->scene('add')->check($data)){
//            $this->error($validate->getError());
        }

        //获取经纬度
        $lngLat = \Map::getLngLat($data['address']);
        if(empty($lngLat) || $lngLat['status'] !=0 || $lngLat['result']['precise'] !=1) {
            $this->error('无法获取数据，或者匹配的地址不精确');
        }

        //判断用户是否存在
        $accountResult=model('BisAccount')->get(['username'=>$data['username']]);
        if($accountResult){
            $this->error('用户已经存在，请你再写');
        }

        //商户基本信息入库
        $bisData = [
            //htmlentities 字符转换为HTML实体
            'name' => $data['name'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
            'logo' => $data['logo'],
            'licence_logo' => $data['licence_logo'],
            'description' => empty($data['description']) ? '' : $data['description'],
            'bank_info' =>  $data['bank_info'],
            'bank_user' =>  $data['bank_user'],
            'bank_name' =>  $data['bank_name'],
            'faren' =>  $data['faren'],
            'faren_tel' =>  $data['faren_tel'],
            'email' =>  $data['email'],
        ];
        $bisId = model('Bis')->add($bisData);
//        print_r($bisData);
        //总店相关信息检验
        $data['cat']='';
        if (!empty($data['se_category_id'])){
            $data['cat'] = implode("|",$data['se_category_id']);  //数组转字符串
        }

        //总店相关信息入库
        $locationData = [
            'bis_id' => $bisId,
            'name' => $data['name'],
            'logo' => $data['logo'],
            'tel' => $data['tel'],
            'contact' => $data['contact'],
            'category_id' => $data['category_id'],
            'category_path' => $data['category_id'] . ',' . $data['cat'],
            'city_id' => $data['city_id'],
            'city_path' => empty($data['se_city_id']) ? $data['city_id'] : $data['city_id'].','.$data['se_city_id'],
            'address' => $data['address'],
            'open_time' => $data['open_time'],
            'content' => empty($data['content']) ? '' : $data['content'],
            'is_main' => 1,// 代表的是总店信息
            'api_address' => $data['address'],
            'bank_info' =>  $data['bank_info'],
            'xpoint' => empty($lngLat['result']['location']['lng']) ? '' : $lngLat['result']['location']['lng'],
            'ypoint' => empty($lngLat['result']['location']['lat']) ? '' : $lngLat['result']['location']['lat'],
        ];

        $locationId = model('BisLocation')->add($locationData);

        //账号相关信息检验
        //自动生成 密码的加盐字符串
        $data['code'] = mt_rand(100, 10000);

        $accountId = [
            'bis_id' => $bisId,
            'username' => $data['username'],
            'code' => $data['code'],
            'password' => md5($data['password'].$data['code']),
            'is_main' => 1, // 代表的是总管理员
        ];
        $accountId=model('BisAccount')->add($accountId);
        if(!$accountId){
            $this->error('申请失败');
        }
        //发送邮件
        $url=request()->domain().url('bis/register/waiting',['id'=>$bisId]);
        $title='o2o入驻申请通知';
        $content="你提交的入驻申请等待平台审核，你可以通过点击连接<a href='".$url."' target='_blank'>查看连接</a>查看审核状态";
        \phpmailer\Email::send($data['email'],$title,$content);
        $this->success('申请成功',url('register/waiting',['id'=>$bisId]));
    }

    public function waiting($id)
    {
//        echo 'ok';
        if(empty($id)){
            $this->error('error');
        }

        $detail=model('Bis')->get($id);

        return $this->fetch('',['detail'=>$detail]);
    }
}