<?php
/**
 * 应用公共文件
 */

/**
 * @param $status
 * @param $message
 * @param array $data
 * @return array
 */
function show($status,$message='',$data=[])
{
    return [
        'status'=>intval($status),
        'message'=>$message,
        'data'=>$data,
    ];
}