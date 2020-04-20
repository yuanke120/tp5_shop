<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------ya

use app\common\model\City as CityModel;

    // 应用公共文件
    /**
     * 状态
     * @param $status
     * @return string
     */
    function status($status)
    {
        if($status==1) {
            $str = "<span class='label label-success radius'>正常</span>";
        }elseif($status == 0){
            $str = "<span class='label label-danger radius'>待审</span>";

        }else{
            $str = "<span class='label label-danger radius'>删除</span>";
        }
        return $str;
    }

    /**
     * 总店状态
     * @param $isMain
     * @return mixed
     */
    function isMain($isMain)
    {
        if($isMain==1){
            $str="<span class='label label-success radius'>是</span>";
        }else{
            $str="<span class='label label-danger radius'>否</span>";
        }
            return $str;
    }

    /**
     * 获取地图
     * @param $url
     * @param int $type 0 get 1 post
     * @param array $data
     * @return bool|string
     */
    function doCurl($url,$type=0,$data=[])
    {
        $ch=curl_init(); //初始化
        //设置选项
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);

        if($type == 1){
            //post
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }
        //  执行并获取内容
        $output = curl_exec($ch);
        // 释放curl
        curl_close($ch);
        return $output;
    }

    /**
     * 商户入驻申请的文案
     * @param $status
     * @return string
     */
    function bisRegister($status)
    {
        if($status==1){
            $str="<font color='red'>恭喜你入驻成功</font>";
        }elseif($status==0){
            $str="<font color='blue'>待审核，审核后平台方会发送邮件通知，请关注邮件</font>";
        }elseif($status==2){
            $str="<font color='#ff8c00'>非常抱歉，您提交的材料不符合条件，请重新提交</font>";
        }else{
            $str = "该申请已被删除";
        }
        return $str;
    }

    /**
     * 通过分页样式
     * @param $obj
     * @return string
     */
    function pagination($obj)
    {
        if(!$obj){
            return '';
        }
        //优化的方案
        $params=request()->param();
        return '<div class="container">
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">'.$obj->appends($params)->render().'</div></div>
				<div class="col-md-4"></div>
			</div>
		</div>';
    }

    /**
     * 城市分级二级
     * @param $path
     * @return false|string
     * @throws \think\exception\DbException
     */
    function getSeCityName($path)
    {
        if(empty($path)){
            return  '';
        }
        //匹配逗号后面的字段值
        if(preg_match('/,/',$path)){
            $cityPath=explode(',',$path);
            $cityId=$cityPath[1];
        }else{
            $cityId=$path;
        }
        $city=model('City')->get($cityId);
        return $city->name;
    }

    /**
     * 分类二级分级
     * @param $path
     * @return false|string
     * @throws \think\exception\DbException
     */
    function getSeCategoryName($path){
        if(empty($path)){
            return '';
        }
        //匹配逗号后面的字段值 利用category表 id来代替category_id
        if(preg_match_all('/,/',$path)){
            $categoryPath=explode(',',$path);
            $categoryId=$categoryPath[1];
        }else{
            $categoryId=$path;
        }
        $category=model('Category')->get($categoryId);
        return $category->name;
    }

    /**
     * 分类
     * @param $ids
     * @return int
     */
    function countLocation($ids)
    {
        if(!$ids){
            return 1;
        }
        if(preg_match('/,/',$ids)){
            $arr=explode(',',$ids);
            return count($arr);
        }
    }

    //设置订单号
    function setOrderSn()
    {
        list($t1,$t2)=explode(' ',microtime());
        $t3=explode('.',$t1*10000);
        return $t2.$t3[0].(rand(10000,99999));
    }


