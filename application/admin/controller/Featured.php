<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\admin\controller\Base as BaseController;
class Featured extends BaseController
{
    /**
     * @var \app\common\model\Featured
     */
    private $bis_featured_model;

    public function _initialize()
    {
        parent::_initialize();
        $this->bis_featured_model=model('Featured');
    }

    /**
     * 推荐列表
     * @return mixed
     */
    public function index()
    {
        //获取推荐类别
        $types = config('featured.featured_type');
        $type = input('get.type',0,'intval');
        //获取列表数据
        $result = $this->bis_featured_model->getFeaturedByType($type);

        return $this->fetch('',[
            'type'=>$types,
            'result'=>$result,
        ]);
    }

    /**
     * 推荐新增
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request)
    {
        if($request->isPost()){
            //入库的逻辑
            $data=input('post.');
            //数据严格考验，validate 自行完成

            $id = $this->bis_featured_model->add($data);
            if($id){
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else {
            $types = config('featured.featured_type');
            return $this->fetch('', [
                'type' => $types
            ]);
        }
    }

    /**
     * 修改状态
     */
//    public function  status()
//    {
//        $data=input('get.');
//        //validate 做个验证 id  status
//
//        $result= $this->bis_featured_model->save(['status'=>$data['status']],['id'=>$data['id']]);
//        if($result){
//            $this->success('更新成功');
//        }else{
//            $this->error('更新失败');
//        }
//    }
}