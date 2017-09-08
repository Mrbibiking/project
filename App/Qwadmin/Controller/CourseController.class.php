<?php
/**
 *
 * 版权所有：恰维网络<qwadmin.qiawei.com>
 * 作    者：寒川<hanchuan@qiawei.com>
 * 日    期：2016-01-19
 * 版    本：1.0.0
 * 功能说明：升级控制器。
 *
 **/

namespace Qwadmin\Controller;

class CourseController extends ComController
{

    public function index()
    {
        $model=M('course_info');
        $count=$model->count();
        $page=new \Think\Page($count,10);
        isset($_GET['p'])?$_GET['p']:1;
        $list=$model->page($_GET['p'].',3')->select();
        $showPage=$page->show();
        $this->assign('page',$showPage);
        $this->assign('data',$list);
        
        $this->display();
    }
    //新增页面
    public function add()
    {

        $option = M('course_info')->order('id ASC')->select();
        $option = $this->getMenu($option);
        $this->assign('option', $option);
        $this->display();
    }
    //新增
    public function insert()
    {
        $model=M('course_info');
        $data['course_number'] = I('post.course_number', '', 'intval');
        $data['course_name'] = I('post.course_name', '', 'strip_tags');
        $data['course_description'] = I('course_description', '', 'strip_tags');
        $data['course_type_number'] = I('post.course_type_number', '', 'intval');
        // $model->create();
        if ($model->add($data)) {
            $this->success('操作成功！',U("index"));
        } else {
            $this->error('操作失败！');
        }
    }
    //修改页面
    public function edit($id=0){
        $id = intval($id);
        $m = M('course_info');
        $currentmenu = $m->where("id='$id'")->find();
        if (!$currentmenu) {
            $this->error('参数错误！');
        }

        $option = $m->order('id')->select();
        $option = $this->getMenu($option);
        $this->assign('option', $option);
        $this->assign('currentmenu', $currentmenu);
        $this->display();
    }
    //修改
    public function update(){
        $id = I('post.id', '', 'intval');
        $data['course_number'] = I('post.course_number', '', 'intval');
        $data['course_name'] = I('post.course_name', '', 'strip_tags');
        $data['course_description'] = I('course_description', '', 'strip_tags');
        $data['course_type_number'] = I('post.course_type_number', '', 'intval');
        if (M('course_info')->data($data)->where("id='{$id}'")->save()) {
            $this->success('操作成功！',U("index"));
        } else {
            $this->error('操作失败！');
        }  
    }
    //删除
    public function del(){
        $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : false;
        if ($ids) {
            if (is_array($ids)) {
                $ids = implode(',', $ids);
                $map['id'] = array('in', $ids);
            } else {
                $map = 'id=' . $ids;
            }
            if (M('course_info')->where($map)->delete()) {
                $this->success('删除成功！');
            } else {
                $this->error('参数错误！');
            }
        } else {
            $this->error('参数错误！');
        }
    }
    
}