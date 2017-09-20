<?php


namespace app\mobile\controller;
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
        $id = input('get.id/d',0);
        if($id)
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
        $person_list = Db::name('person')->field('id,name,thumb,job,content')
            ->where('is_open',1)
            ->order("id desc")
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        //处理数据
        foreach($person_list as &$v){
            $v['thumb'] = person_thum_images($v['id'],280,500);
            $v['name'] = getSubstr($v['name'],0,5);
            $v['job'] = getSubstr($v['job'],0,7);
            $v['content'] = getSubstr($v['content'],0,20);
        }
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
        $goods_list = Db::name('goods')->field('id,name,thumb')
            ->where('is_open',1)
            ->order("id desc")
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        //处理数据
        foreach($goods_list as &$v){
            $v['thumb'] = goods_thum_images($v['id'],768,506);
            $v['name'] = getSubstr($v['name'],0,7);
        }
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
            ->field('act_id,act_name,act_img,start_time,act_desc,end_time')
            ->where('enable',1)
            ->where('a.start_time','<',time())
            ->where('a.start_time','>',time()-3600*24*30)
            ->where('a.end_time','>',time())
            ->order('act_id desc')
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        //处理数据
        foreach($activity_list as &$v){
            $v['start_time'] = formatActDate($v['start_time']);
            $v['act_name'] = getSubstr($v['act_name'],0,8);
            $v['act_desc'] = getSubstr($v['act_desc'],0,40);
        }
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
            ->field('act_id,act_name,act_img,start_time,act_desc,end_time')
            ->where('enable',1)
            ->where('a.start_time','<',time()-3600*24*30)
            ->order('act_id desc')
            ->cache(true,JT_CACHE_TIME)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        //处理数据
        foreach($activity_list as &$v){
            $v['start_time'] = formatActDate($v['start_time']);
            $v['act_name'] = getSubstr($v['act_name'],0,8);
            $v['act_desc'] = getSubstr($v['act_desc'],0,40);
        }
        $data = [
            'data'=>$activity_list,
            'count'=>$count
        ];
        return json($data);
    }
    



    
}