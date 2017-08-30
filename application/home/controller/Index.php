<?php
namespace app\home\controller; 
use think\Controller;
use think\Db;

class Index extends Base {
    /**
     * 首页
     * @return mixed
     */
    public function index(){

        //初始化
        $index_banner  = [];

        //全屏banner
        $index_banner = S('index_banner');
        if(empty($index_banner))
        {
            $index_banner = Db::name('banner')->alias('a')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('a.pid',1)
                ->order('a.orderby desc')
                ->select();
            S('index_banner',$index_banner,JT_CACHE_TIME);
        }
        $this->assign('index_banner',$index_banner);
        //首页活动

        //品牌
        $brand_list = range('A','Z');
        $this->assign('brand_list',$brand_list);

        return $this->fetch();
    }
}