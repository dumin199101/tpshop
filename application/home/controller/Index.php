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
        $index_banner  = array();
        $index_banner = S('index_banner');
        if(empty($index_banner))
        {
            $index_banner = Db::name('banner')->alias('a')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enable',1)
                ->where('a.start_time','<',now())
                ->where('a.end_time','>',now())
                ->order('a.orderby desc')
                ->select();
            S('index_banner',$index_banner,JT_CACHE_TIME);
        }
        $this->assign('index_banner',$index_banner);
        return $this->fetch();
    }
}