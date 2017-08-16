<?php

namespace app\admin\controller;

use think\Page;


class Activity extends Base
{

    /**
     * 活动列表
     */
    public function activityList()
    {
        $count = M('activity')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $activity_list = M('activity')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('pager',$Page);
        $this->assign('page', $show);// 赋值分页输出
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