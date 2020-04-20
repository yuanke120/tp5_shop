<?php
namespace app\common;

/**
 * 加密密码
 * Class common
 * @package app\common
 */
class common{
    public static function encrypt_password($str)
    {
        return md5($str);
    }
}