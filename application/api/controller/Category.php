<?php
namespace app\api\controller;

use think\Controller;

/**
 * Class Category
 * @package app\api\controller
 */
class Category extends Controller
{
    /**
     * @var \app\common\model\Category
     */
    private $category_model;

    public function _initialize()
    {
        parent::_initialize();
        $this->category_model=model('Category');
    }

    public function getCategoryByParentId()
    {
        $id=input('post.id',0,'intval');
        if(!$id){
            $this->error('ID不合法');
        }
        //通过获取id分类
        $category=$this->category_model->getNormalCategoryByParentId($id);
        if(!$category){
            return show(0,'error');
        }
            return show(1,'success',$category);
        }

}