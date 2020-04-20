<?php
/**
 * 百度地图业务封装
 * Author:YuanKe
 */

class Map
{
    /**
     * 根据地址获取经纬度
     * @param $address
     * @return array
     */
    public static function getLngLat($address)
    {
        if(!$address){
            return '';
        }
        $data=[
            'address'   =>$address,
            'ak'=>config('map.ak'),
            'output'    =>'json',
        ];
        //生成url_encode后请求字符串
        $url=config('map.map_url').config('map.geocoding').'?'.http_build_query($data);
        $result= doCurl($url);
//         print_r($result);exit;
        if($result){
            return json_decode($result,true);
        }else{
            return [];
        }
//        return $result;
    }

    /**
     * 根据纬度或者地址来获取地图
     * @param $center
     * @return bool|string
     */
    public static  function staticImage($center)
    {
        if(!$center){
            return '';
        }
        $data=[
            'ak'     =>config('map.ak'),
            'width'  =>config('map.width'),
            'height' =>config('map.height'),
            'center' =>$center,
            'markers'=>$center,
        ];
        //生成url_encode后请求字符串
        $url=config('map.map_url').config('map.staticimage').'?'.http_build_query($data);
        $result= doCurl($url);
        return $result;
    }
}