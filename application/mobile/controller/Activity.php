<?php
namespace app\mobile\controller;
use think\Db;
use think\Request;

class Activity extends Base {
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $position = Request::instance()->controller();
        $this->assign('position',$position);
    }


    /**
     * 活动列表
     */
    public function index()
    {
        //全屏banner
        $banner_list = S('Pos:activity:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target,a.banner_name,a.banner_desc,a.start_time,a.end_time')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%活动列表页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:activity:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list',$banner_list);

        //最新活动4个(最近一个月内发布)
        $act_latest_list = S('Pos:activity:act_latest_list');
        if(empty($act_latest_list)){
            $act_latest_list = Db::name('activity')->field('act_id,act_name,act_img,start_time,end_time')
                ->where('enable',1)
                ->where('start_time','<',time())
                ->where('start_time','>',time()-3600*24*30)
                ->where('end_time','>',time())
                ->order('act_id desc')
                ->limit(4)
                ->select();
            S('Pos:activity:act_latest_list',$act_latest_list,JT_CACHE_TIME);
        }
        $this->assign('act_latest_list',$act_latest_list);

        //往期活动4个（一个月之外发布）
        $act_oldest_list = S('Pos:activity:act_oldest_list');
        if(empty($act_oldest_list)){
            $act_oldest_list = Db::name('activity')->field('act_id,act_name,act_img,start_time,end_time')
                ->where('enable',1)
                ->where('start_time','<',time()-3600*24*30)
                ->order('act_id desc')
                ->limit(4)
                ->select();
            S('Pos:activity:act_oldest_list',$act_oldest_list,JT_CACHE_TIME);
        }
        $this->assign('act_oldest_list',$act_oldest_list);
        return $this->fetch();

    }

    /**
     * 最新活动
     */
    public function latest()
    {
        //最新活动4个
        $act_latest_list = Db::name('activity')->field('act_id,act_name,act_img,start_time,act_desc')
            ->where('enable',1)
            ->where('start_time','<',time())
            ->where('start_time','>',time()-3600*24*30)
            ->where('end_time','>',time())
            ->order('act_id desc')
            ->limit(4)
            ->cache(true,JT_CACHE_TIME)
            ->select();
        $this->assign('act_latest_list',$act_latest_list);
        return $this->fetch();
    }

    /**
     * 以往活动
     */
    public function oldest()
    {
        $act_oldest_list = Db::name('activity')->field('act_id,act_name,act_img,start_time,act_desc')
            ->where('enable',1)
            ->where('start_time','<',time()-3600*24*30)
            ->order('act_id desc')
            ->limit(4)
            ->cache(true,JT_CACHE_TIME)
            ->select();
        $this->assign('act_oldest_list',$act_oldest_list);
        return $this->fetch();
    }

    /**
     * 活动详情
     */
    public function info()
    {
        //全屏banner
        $banner_list = S('Pos:actInfo:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%活动详情页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:actInfo:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list',$banner_list);

        $id = input('param.id/d',1);
        $act_info = Db::name('activity')->field('act_id,act_name,act_img,start_time,act_content,end_time')
            ->where('act_id',$id)
            ->cache(true,JT_CACHE_TIME)
            ->find();
        $this->assign('act_info',$act_info);
        return $this->fetch();
    }

}