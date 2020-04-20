<?php
/**
 * logic基类
 */
//应用公共文件
namespace app\admin;

class common
{
    /**
     * 返回json数据
     * @param $code
     * @param $message
     * @param $url
     * @return array
     */
    public static function return_result($code,$message,$url)
    {
        $result=[
            'code'  =>$code,
            'message'=>$message,
            'url'=>$url,
        ];
        return $result;
    }

}


