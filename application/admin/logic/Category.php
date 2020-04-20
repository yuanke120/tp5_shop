<?php
namespace app\admin\logic;

use app\admin\common\logic\BaseLogic;
use app\admin\common;
use app\admin\common\logic\ILogic;
/**
 * Class Category
 * @package app\admin\logic
 */
class Category extends BaseLogic implements ILogic
{
    /**
     * @var \app\common\model\Category
     */
    private $category_model;

    /**
     * @var \app\admin\validate\Category
     */
    protected $validate;

    public function initialize()
    {
        parent::initialize();
        $this->category_model=model('Category');
        $this->validate=validate('Category','validate');
    }

    public function add($data)
    {
        $data['status'] = 10;
        if (!$this->validate->scene('add')->check($data)) {
            return common::return_result('500', $this->validate->getError(), null);
        } else {
           $result=$this->category_model->add($data);
            return $result ?
                common::return_result('200',ADD_SUCCESS_TXT,'Category/add') :
                common::return_result('500',ADD_FAILURE_TXT,null);
        }
    }
}