<?php
/**
 * ============================================================================
 *   轮播管理
 * ============================================================================
 * Author: lieyan123091
 * Date: 2017-07-20
 */
namespace app\admin\controller;

use think\Db;
use think\Page;

class Banner extends Base
{

    /**
     * 增删改轮播
     */
    public function bannerHandle()
    {
        $data = I('post.');
        $data['start_time'] = strtotime($data['begin']);
        $data['end_time'] = strtotime($data['end']);

        if ($data['act'] == 'add') {
            $r = D('banner')->add($data);
        }
        if ($data['act'] == 'edit') {
            $r = D('banner')->where('banner_id', $data['ad_id'])->save($data);
        }

        if ($data['act'] == 'del') {
            $r = D('banner')->where('banner_id', $data['del_id'])->delete();
            if ($r) exit(json_encode(1));
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Banner/bannerList');
        // 不管是添加还是修改广告 都清除一下缓存
        delFile(RUNTIME_PATH . 'html'); // 先清除缓存, 否则不好预览
        \think\Cache::clear();
        if ($r) {
            $this->success("操作成功", U('Admin/Banner/bannerList'));
        } else {
            $this->error("操作失败", $referurl);
        }
    }


    /**
     * 增删改轮播位置
     */
    public function positionHandle()
    {
        $data = I('post.');
        if ($data['act'] == 'add') {
            $r = M('banner_position')->add($data);
        }

        if ($data['act'] == 'edit') {
            $r = M('banner_position')->where('position_id', $data['position_id'])->save($data);
        }

        if ($data['act'] == 'del') {
            if (M('banner')->where('pid', $data['position_id'])->count() > 0) {
                $this->error("此位置下还有焦点图，请先清除", U('Admin/Banner/positionList'));
            } else {
                $r = M('banner_position')->where('position_id', $data['del_id'])->delete();
                if ($r) exit(json_encode(1));
            }
        }
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U('Admin/Banner/positionList');
        if ($r) {
            $this->success("操作成功", U('Admin/Banner/positionList'));
        } else {
            $this->error("操作失败", $referurl);
        }
    }

    /**
     * 轮播图列表
     * @return mixed
     */
    public function bannerList()
    {

        delFile(RUNTIME_PATH . 'html'); // 先清除缓存, 否则不好预览
        $Banner = M('banner');
        $pid = I('pid', 0);
        if ($pid) {
            $where['pid'] = $pid;
            $this->assign('pid', I('pid'));
        }
        $keywords = I('keywords/s', false, 'trim');
        if ($keywords) {
            $where['banner_name'] = array('like', '%' . $keywords . '%');
        }
        $count = $Banner->where($where)->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $res = $Banner->where($where)->order('pid desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $list = array();
        if ($res) {
            $media = array('图片', '文字', 'flash');
            foreach ($res as $val) {
                $val['media_type'] = $media[$val['media_type']];
                $list[] = $val;
            }
        }

        $banner_position_list = M('BannerPosition')->getField("position_id,position_name,is_open");
        $this->assign('banner_position_list', $banner_position_list);//广告位
        $show = $Page->show();// 分页显示输出
        $this->assign('list', $list);// 赋值数据集
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $pager);
        return $this->fetch();
    }

    /**
     * 新增轮播表单
     * @return mixed
     */
    public function banner()
    {
        $act = I('get.act', 'add');
        $banner_id = I('get.banner_id/d');
        $ad_info = array();
        if ($banner_id) {
            $banner_info = D('banner')->where('banner_id', $banner_id)->find();
            $banner_info['start_time'] = date('Y-m-d', $banner_info['start_time']);
            $banner_info['end_time'] = date('Y-m-d', $banner_info['end_time']);
        }
        if ($act == 'add')
            $banner_info['pid'] = $this->request->param('pid');
        $position = D('banner_position')->select();
        $this->assign('info', $ad_info);
        $this->assign('act', $act);
        $this->assign('position', $position);
        return $this->fetch();
    }

    /**
     * 新增轮播位置表单
     * @return mixed
     */
    public function position()
    {
        $act = I('get.act', 'add');
        $position_id = I('get.position_id/d');
        $info = array();
        if ($position_id) {
            $info = D('banner_position')->where('position_id', $position_id)->find();
        }
        $this->assign('info', $info);
        $this->assign('act', $act);
        return $this->fetch();
    }

    /**
     * 轮播列表
     * @return mixed
     */
    public function positionList()
    {
        $count = Db::name('banner_position')->count();// 查询满足要求的总记录数
        $Page = $pager = new Page($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = Db::name('banner_position')->order('position_id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('pager', $Page);
        return $this->fetch();
    }

    /**
     * 轮播列表跳转到指定页
     */
    public function editBanner()
    {
        \think\Cache::clear();
        $request_url = urldecode(I('request_url'));
        $request_url = U($request_url, array('edit_banner' => 1));
        echo "<script>location.href='" . $request_url . "';</script>";
        exit;
    }
}