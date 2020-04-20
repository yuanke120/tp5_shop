<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\controller\Base as BaseController;

/**
 * Class Category
 * @package app\admin\controller
 */
class Category extends BaseController
{
//    /**
//     * @var \app\admin\logic\Category
//     */
//    private $category_logic;

    /**
     * @var \app\common\model\Category
     */
    private $category_model;

    public function _initialize()
    {
        parent::_initialize();
//        $this->category_logic=model('Category','logic');
        $this->category_model=model('Category');
    }

    public function index()
    {
        $parent_id=input('get.parent_id',0,'intval');
        $category_list=$this->category_model->getCategorys($parent_id);
        return $this->fetch('',['category_list'=>$category_list]);
    }

    public function add()
    {
        $categorys=$this->category_model->getCategory();
        return $this->fetch('',['categorys'=>$categorys]);
    }

    public function save(Request $request)
    {
        if(!request()->isPost()){
            $this->error('请求错误');
        }
        $data=input('post.');
       // $date['status']=10;

        $validate=validate('Category');
        $data['name']=htmlentities($data['name']);
        if(!$validate->scene('add')->check($data)){
            $this->error($validate->getError());
        }
        if(!empty($data['id'])){
            return $this->update($data);
        }
        //把data提交model层
        $result=$this->category_model->add($data);
        if($result){
            $this->success('新增成功');
        }else{
            $this->error('新增失败');
        }
        //  logic接口
//        if(request()->isPost()){
//            return json($this->category_logic->add(input('post.')));
//        }else{
//            return $this->fetch();
//        }

    }


    public function edit($id=0)
    {
        if(intval($id)<1){
            $this->error('参数不合法');
        }else{
           $category = $this->category_model->get($id);
           $categorys= $this->category_model->getCategory();
            return $this->fetch('',[
                'categorys' =>$categorys,
                'category'=>$category
            ]);
        }
    }

    public function update($data)
    {
        $result=$this->category_model->save($data,['id'=>intval($data['id'])]);
        return $result ?
            $this->success('更新成功') :
            $this->error('更新失败');
    }

    //排序逻辑
    public function listorder($id,$listorder)
    {
       $result=$this->category_model->save(['listorder'=>$listorder],['id'=>$id]);
        if($result) {
            //API数据到json客户端
            //https://segmentfault.com/q/1010000011792748 $this->result()函数
            $this->result($_SERVER['HTTP_REFERER'],1,'更新成功');
        }else{
            $this->result($_SERVER['HTTP_REFERER'],0,'更新失败');
        }

    }

    /**
     * 修改状态
     */
//    public function status()
//    {
//        $data= input('get.');
//        $validate=validate('Category');
//        if(!$validate->scene('status')->check($data)){
//            $this->error($validate->getError());
//        }
//        //-1 删除 0 等待 1 正常
//        $result=$this->category_model->save(['status'=>$data['status']],['id'=>$data['id']]);
//        if($result){
//            $this->success("状态更新成功");
//        }else{
//            $this->error("状态更新失败");
//        }
//    }

//    public function del(Request $request){
//        $category_id=$request->param('id');
//        if($category_id){
//            $this->category_model->destroy(['id'=>$category_id]);
//            $this->success('删除成功');
//        }else{
//            $this->error('删除失败');
//        }
//    }

}