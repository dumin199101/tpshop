<?php

namespace app\admin\controller;

use think\Page;
use think\Db;


class Activity extends Base
{

    /**
     * 批量删除、显示、推荐活动
     */
    public function batchOpActivity()
    {
        $type = I('post.type');
        $selected_id = I('post.selected/a');
        if(!in_array($type,array('del','show','hide','recommend','no-recommend','hot','no-hot')) || !$selected_id)
            $this->error('非法操作');
        if($type == 'del'){
            //删除
            $row = Db::name('activity')->where('act_id','IN',$selected_id[0])->delete();
        }
        if($type == 'show'){
            $row = Db::name('activity')->where('act_id','IN',$selected_id[0])->save(array('enable'=>1));
        }
        if($type == 'hide'){
            $row = Db::name('activity')->where('act_id','IN',$selected_id[0])->save(array('enable'=>0));
        }
        if($type == 'recommend'){
            $row = Db::name('activity')->where('act_id','IN',$selected_id[0])->save(array('is_recommend'=>1));
        }
        if($type == 'no-recommend'){
            $row = Db::name('activity')->where('act_id','IN',$selected_id[0])->save(array('is_recommend'=>0));
        }
        if($type == 'hot'){
            $row = Db::name('activity')->where('act_id','IN',$selected_id[0])->save(array('is_hot'=>1));
        }
        if($type == 'no-hot'){
            $row = Db::name('activity')->where('act_id','IN',$selected_id[0])->save(array('is_hot'=>0));
        }
        if(!$row)
            $this->error('操作失败');
        $this->success('操作成功');
    }

    /**
     * 活动列表
     */
    public function activityList()
    {
        $where = array();
        $keywords = trim(I('keywords'));
        $keywords && $where['act_name'] = array('like', '%' . $keywords . '%');
        $filter_var = I('post.filter_var',0);
        if(!empty($filter_var)){
            if($filter_var==1)
                $where['is_recommend'] = 1;
            if($filter_var==2)
                $where['is_hot'] = 1;
        }
        $count = M('activity')->where($where)->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $activity_list = M('activity')->where($where)->order('act_id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('pager',$Page);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('filter_var',$filter_var);
        $this->assign('activity_list', $activity_list);
        return $this->fetch();
    }

    /**
     * 添加编辑活动表单
     * @return mixed
     */
    public function activity()
    {
        $activity_id = I('id');
        $info['start_time'] = date('Y-m-d');
        $info['end_time'] = date('Y-m-d', time() + 3600 * 60 * 24);
        if ($activity_id > 0) {
            $info = M('activity')->where("act_id=$activity_id")->find();
            $info['start_time'] = date('Y-m-d', $info['start_time']);
            $info['end_time'] = date('Y-m-d', $info['end_time']);
        }
        $this->assign('info', $info);
        $this->assign('min_date', date('Y-m-d'));
        $this->initEditor();
        return $this->fetch('_activity');
    }

    public function activity_save()
    {
        $activity_id = I('id');
        $data = I('post.');
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if($data['start_time']>=$data['end_time']){
            $this->error('开始时间不能大于结束时间', U('Activity/activityList'));
        }
        if ($activity_id) {
            M('activity')->where("act_id", $activity_id)->save($data);
            adminLog("管理员修改了活动 " . I('act_name'));
        } else {
            M('activity')->add($data);
            adminLog("管理员添加了活动 " . I('act_name'));
        }

        $this->success('编辑活动成功', U('Activity/activityList'));
    }

    public function activity_del()
    {
        $activity_id = I('id');
        M('activity')->where("act_id=$activity_id")->delete();
        adminLog("删除活动");
        $this->success('删除活动成功', U('Activity/activityList'));
    }

    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp', array('savepath' => 'activity')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp', array('savepath' => 'activity')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp', array('savepath' => 'activity')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage', array('savepath' => 'activity')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager', array('savepath' => 'activity')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp', array('savepath' => 'activity')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie', array('savepath' => 'activity')));
        $this->assign("URL_Home", "");
    }


}