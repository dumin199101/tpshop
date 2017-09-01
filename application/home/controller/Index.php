<?php
namespace app\home\controller;
use think\Db;

class Index extends Base {
    /**
     * 首页
     * @return mixed
     */
    public function index(){

        //全屏banner
        $banner_list = S('Pos:index:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%首页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:index:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list',$banner_list);

        //活动
        $act_list = S('Pos:index:act_list');
        if(empty($act_list)){
            $act_list = Db::name('activity')->alias('a')
                ->field('act_id,act_name,act_img,start_time')
                ->where('is_recommend',1)
                ->where('enable',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->order('act_id desc')
                ->limit(8)
                ->select();
            S('Pos:index:act_list',$act_list,JT_CACHE_TIME);
        }
        $this->assign('act_list',$act_list);

        //BRAND_品牌
        $letter_list = range('A','Z');
        $this->assign('letter_list',$letter_list);

        //太潮人：取出4个
        $person_list = S('Pos:index:person_list');
        if(empty($person_list)){
            $person_list = Db::name('person')->field('id,name,thumb,job,content')
                ->where('is_recommend',1)
                ->where('is_open',1)
                ->limit(4)
                ->select();
            S('Pos:index:person_list',$person_list,JT_CACHE_TIME);
        }
        $this->assign('person_list',$person_list);

        //太潮志:取出3个
        $goods_list = S('Pos:index:goods_list');
        if(empty($goods_list)){
            $goods_list = Db::name('goods')->field('id,name,thumb')
                ->where('is_recommend',1)
                ->where('is_open',1)
                ->order('sort')
                ->limit(3)
                ->select();
            S('Pos:index:goods_list',$goods_list,JT_CACHE_TIME);
        }
        $this->assign('goods_list',$goods_list);
        return $this->fetch();
    }
}