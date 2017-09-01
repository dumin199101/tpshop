<?php


namespace app\home\controller;
use think\Db;
use think\Page;
use think\Session;
use think\Controller;


class Api extends Controller {

    public function _initialize() {
        Session::start();
    }

    /**
     * 获取品牌详情信息
     */
    public function ajaxGetBrandInfo()
    {
        $id = input('get.id/d',1);
        $brand_info = Db::name('brand')->field('title,name,attach,desc')
            ->where('id',$id)
            ->cache(true,JT_CACHE_TIME)
            ->find();
        return json($brand_info);
    }

    /**
     * ajax分页获取太潮人信息
     */
    public function ajaxGetPersonList()
    {
        $count = Db::name('person')->where('is_open',1)->count();
        $page = new Page($count,8);
        $person_list = Db::name('person')->where('is_open',1)
            ->order("id")
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $data = [
            'data'=>$person_list,
            'count'=>$count
        ];
        return json($data);
    }

    /**
     * ajax分页获取太潮志信息
     */
    public function ajaxGetGoodsList()
    {
        $count = Db::name('goods')->where('is_open',1)->count();
        $page = new Page($count,9);
        $goods_list = Db::name('goods')->where('is_open',1)
            ->order("id")
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $data = [
            'data'=>$goods_list,
            'count'=>$count
        ];
        return json($data);
    }
    
    /**
     * ajax分页获取最新活动
     */
    public function ajaxGetLatestList()
    {
        $count =  Db::name('activity')
            ->alias('a')
            ->where('enable',1)
            ->where('a.start_time','<',time())
            ->where('a.start_time','>',time()-3600*24*30)
            ->where('a.end_time','>',time())
            ->count();
        $page = new Page($count,4);
        $activity_list =  Db::name('activity')->alias('a')
            ->field('act_id,act_name,act_img,start_time,act_desc')
            ->where('enable',1)
            ->where('a.start_time','<',time())
            ->where('a.start_time','>',time()-3600*24*30)
            ->where('a.end_time','>',time())
            ->order('act_id desc')
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $data = [
            'data'=>$activity_list,
            'count'=>$count
        ];
        return json($data);
    }
    
    /**
     * ajax分页获取往期活动
     */
    public function ajaxGetOldestList()
    {
        $count =  Db::name('activity')->alias('a')
            ->where('enable',1)
            ->where('a.start_time','<',time()-3600*24*30)
            ->count();
        $page = new Page($count,4);
        $activity_list =  Db::name('activity')->alias('a')
            ->field('act_id,act_name,act_img,start_time,act_desc')
            ->where('enable',1)
            ->where('a.start_time','<',time()-3600*24*30)
            ->order('act_id desc')
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $data = [
            'data'=>$activity_list,
            'count'=>$count
        ];
        return json($data);
    }
    



    
}