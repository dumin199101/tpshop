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




    
}