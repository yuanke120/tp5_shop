<?php
namespace app\api\controller;

use think\Controller;
use think\Request;
use think\File;

/**
 * Class Image
 * @package app\api\controller
 * 上传文件接口api
 */
class Image extends  Controller
{
    /**
     * @return array
     */
    public function  upload()
    {
        $file=Request::instance()->file('file');
        $info=$file->move('upload');
//        print_r($info);
        if($info && $info->getPathname()){
            return show(1,'success','/'.$info->getPathname());
        }else{
            return show(0,'upload error');
        }
    }

}
