<?php
namespace app\common\validate;

use think\Validate;

/**
 * Class Bis
 * @package app\common\validate
 */
class Bis extends  Validate
{
    /** 验证规则 **/
    protected $rule=[
        'name'      =>'require|max:25',
        'email'     =>'email',
        'logo'      =>'require',
        'city_id'   =>'require',
        'bank_info' =>'require',
        'bank_name' =>'require',
        'bank_user' =>'require',
        'faren'     =>'require',
        'fanren_tel'=>'require'
    ];

    /**验证场景**/
    protected $scene=[
        'add'=>['name','email','logo','city_id','bank_info','bank_name','bank_user','faren','fanren_tel'],
    ];
}