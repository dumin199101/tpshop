<?php

namespace app\admin\controller;

use app\admin\model\GoodsActivity;
use think\Page;


class Promotion extends Base
{

    public function index()
    {
        return $this->fetch();
    }

    /**
     * 商品活动列表
     */
    public function prom_goods_list()
    {
        $parse_type = array('0' => '直接打折', '1' => '减价优惠', '2' => '固定金额出售', '3' => '买就赠优惠券');
        $level = M('user_level')->select();
        if ($level) {
            foreach ($level as $v) {
                $lv[$v['level_id']] = $v['level_name'];
            }
        }
        $this->assign("parse_type", $parse_type);

        $count = M('prom_goods')->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $res = M('prom_goods')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        if ($res) {
            foreach ($res as $val) {
                if (!empty($val['group']) && !empty($lv)) {
                    $val['group'] = explode(',', $val['group']);
                    foreach ($val['group'] as $v) {
                        $val['group_name'] .= $lv[$v] . ',';
                    }
                }
                $prom_list[] = $val;
            }
        }
        $this->assign('pager',$Page);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('prom_list', $prom_list);
        return $this->fetch();
    }

    public function prom_goods_info()
    {
        $level = M('user_level')->select();
        $this->assign('level', $level);
        $prom_id = I('id');
        $info['start_time'] = date('Y-m-d');
        $info['end_time'] = date('Y-m-d', time() + 3600 * 60 * 24);
        if ($prom_id > 0) {
            $info = M('prom_goods')->where("id=$prom_id")->find();
            $info['start_time'] = date('Y-m-d', $info['start_time']);
            $info['end_time'] = date('Y-m-d', $info['end_time']);
            $prom_goods = M('goods')->where("prom_id=$prom_id and prom_type=3")->select();
            $this->assign('prom_goods', $prom_goods);
        }
        $this->assign('info', $info);
        $this->assign('min_date', date('Y-m-d'));
        $this->initEditor();
        return $this->fetch();
    }

    public function prom_goods_save()
    {
        $prom_id = I('id');
        $data = I('post.');
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        if($data['start_time']>=$data['end_time']){
            $this->error('开始时间不能大于结束时间', U('Promotion/prom_goods_list'));
        }
        $data['group'] = $data['group'] ? implode(',', $data['group']) : '';
        $data['goods_ids'] = $data['goods_id'] ? implode(',', $data['goods_id']) : '';
        if ($prom_id) {
            M('prom_goods')->where("id", $prom_id)->save($data);
            $last_id = $prom_id;
            adminLog("管理员修改了商品促销 " . I('name'));
        } else {
            $last_id = M('prom_goods')->add($data);
            adminLog("管理员添加了商品促销 " . I('name'));
        }

        if (is_array($data['goods_id'])) {
            $goods_id = implode(',', $data['goods_id']);
            if ($prom_id > 0) {
                M("goods")->where("prom_id=$prom_id and prom_type=3")->save(array('prom_id' => 0, 'prom_type' => 0));
            }
            M("goods")->where("goods_id in($goods_id)")->save(array('prom_id' => $last_id, 'prom_type' => 3));
        }
        $this->success('编辑促销活动成功', U('Promotion/prom_goods_list'));
    }

    public function prom_goods_del()
    {
        $prom_id = I('id');
        $order_goods = M('order_goods')->where("prom_type = 3 and prom_id = $prom_id")->find();
        if (!empty($order_goods)) {
            $this->error("该活动有订单参与不能删除!");
        }
        M("goods")->where("prom_id=$prom_id and prom_type=3")->save(array('prom_id' => 0, 'prom_type' => 0));
        M('prom_goods')->where("id=$prom_id")->delete();
        $this->success('删除活动成功', U('Promotion/prom_goods_list'));
    }



    public function group_buy_list()
    {
        $Ad = M('group_buy');
        $count = $Ad->count();
        $Page = new Page($count, 10);
        $res = $Ad->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        if ($res) {
            foreach ($res as $val) {
                $val['start_time'] = date('Y-m-d H:i', $val['start_time']);
                $val['end_time'] = date('Y-m-d H:i', $val['end_time']);
                $list[] = $val;
            }
        }
        $this->assign('list', $list);
        $show = $Page->show();
        $this->assign('page', $show);
        $this->assign('pager', $Page);
        return $this->fetch();
    }

    private function initEditor()
    {
        $this->assign("URL_upload", U('Admin/Ueditor/imageUp', array('savepath' => 'promotion')));
        $this->assign("URL_fileUp", U('Admin/Ueditor/fileUp', array('savepath' => 'promotion')));
        $this->assign("URL_scrawlUp", U('Admin/Ueditor/scrawlUp', array('savepath' => 'promotion')));
        $this->assign("URL_getRemoteImage", U('Admin/Ueditor/getRemoteImage', array('savepath' => 'promotion')));
        $this->assign("URL_imageManager", U('Admin/Ueditor/imageManager', array('savepath' => 'promotion')));
        $this->assign("URL_imageUp", U('Admin/Ueditor/imageUp', array('savepath' => 'promotion')));
        $this->assign("URL_getMovie", U('Admin/Ueditor/getMovie', array('savepath' => 'promotion')));
        $this->assign("URL_Home", "");
    }


}