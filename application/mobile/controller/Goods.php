<?php

namespace app\mobile\controller;
use think\Db;
use think\Request;

class Goods extends Base {

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $position = Request::instance()->controller();
        $this->assign('position',$position);
    }


    /**
     * 太潮人+太潮志
     * @return mixed
     */
    public function index(){

        //全屏banner
        $banner_list = S('Pos:goods:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%君太太潮人%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:goods:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list',$banner_list);

        //太潮人：取出4个
        $person_list = S('Pos:goods:person_list');
        if(empty($person_list)){
            $person_list = Db::name('person')->field('id,name,thumb,job,content')
                ->where('is_recommend',1)
                ->where('is_open',1)
                ->limit(4)
                ->select();
            S('Pos:goods:person_list',$person_list,JT_CACHE_TIME);
        }
        $this->assign('person_list',$person_list);

        //太潮志:取出3个
        $goods_list = S('Pos:goods:goods_list');
        if(empty($goods_list)){
            $goods_list = Db::name('goods')->field('id,name,thumb')
                ->where('is_recommend',1)
                ->where('is_open',1)
                ->limit(3)
                ->select();
            S('Pos:goods:goods_list',$goods_list,JT_CACHE_TIME);
        }
        $this->assign('goods_list',$goods_list);
        return $this->fetch();
    }

    /**
     * 太潮人列表页
     * @return mixed
     */
    public function personList()
    {
        //全屏banner
        $banner_list = S('Pos:personList:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%太潮人列表页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:personList:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list',$banner_list);

        //活动
        $act_list = S('Pos:mobile:personList:act_list');
        if(empty($act_list)){
            $act_list = Db::name('activity')->field('act_id,act_name,act_img,start_time')
                ->where('is_hot',1)
                ->where('enable',1)
                ->where('start_time','<',time())
                ->where('end_time','>',time())
                ->order('act_id desc')
                ->limit(4)
                ->select();
            S('Pos:mobile:personList:act_list',$act_list,JT_CACHE_TIME);
        }
        $this->assign('act_list',$act_list);

        //太潮人：取出第一页8个
        $person_list = Db::name('person')->field('id,name,thumb,job,content')
                ->where('is_open',1)
                ->order('id desc')
                ->limit(8)
                ->cache(true,JT_CACHE_TIME)
                ->select();
        $this->assign('person_list',$person_list);
        return $this->fetch();
    }


    /**
     * 太潮人详情页
     * @return mixed
     */
    public function personInfo()
    {
        $id = input('param.id/d',1);
        $person_info = Db::name('person')->field('id,name,content,job,thumb')
            ->where('id',$id)
            ->cache(true,JT_CACHE_TIME)
            ->find();

        //当前太潮人参加的活动：
        $act_list = D('Activity')->alias('a')
            ->field('a.act_id,a.act_name,a.act_img,a.start_time')
            ->join('__PERSON_ACTIVITY__ b','a.act_id=b.act_id')
            ->where('b.person_id',$id)
            ->order('a.start_time desc')
            ->cache(true,JT_CACHE_TIME)
            ->limit(4)
            ->select();


        //更多潮人：
        $sql = "SELECT `id`,`name`,`content`,`job`,`thumb` FROM `__PREFIX__person` WHERE `id` >= ((SELECT MAX(`id`) FROM `__PREFIX__person`)-(SELECT MIN(`id`) FROM __PREFIX__person)) * RAND() + (SELECT MIN(`id`) FROM `__PREFIX__person`) AND `id`!=" . $id .  "  AND `is_open`=1 LIMIT 4";
        $more_list = Db::query($sql);



        $this->assign('more_list',$more_list);
        $this->assign('act_list',$act_list);
        $this->assign('person_info',$person_info);
        return $this->fetch();
    }

    /**
     * 太潮志列表页
     */
    public function goodsList(){
        //全屏banner
        $banner_list = S('Pos:goodsList:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%太潮志列表页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:goodsList:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list',$banner_list);

        //太潮志：取出第一页9个
        $goods_list = Db::name('goods')->field('id,name,thumb')
            ->where('is_open',1)
            ->order('id desc')
            ->limit(9)
            ->cache(true,JT_CACHE_TIME)
            ->select();
        $this->assign('goods_list',$goods_list);
        return $this->fetch();
    }

    /**
    * 太潮志详情页
    */
    public function goodsInfo(){
        /*$key = md5($_SERVER['REQUEST_URI']);
        $html = S($key);
        if(!empty($html))
        {
            return $html;
        }*/
       $id = input('param.id/d',1);
       $goods_info = Db::name('goods')->field('name,content,add_time,thumb,author')
           ->where('id',$id)
           ->cache(true,JT_CACHE_TIME)
           ->find();
       $this->assign('goods_info',$goods_info);
      /* $html = $this->fetch();
       S($key,$html);
       return $html;*/

        //更多太潮志：
        $sql = "SELECT `id`,`name`,`content`,`thumb` FROM `__PREFIX__goods` WHERE `id` >= ((SELECT MAX(`id`) FROM `__PREFIX__goods`)-(SELECT MIN(`id`) FROM __PREFIX__goods)) * RAND() + (SELECT MIN(`id`) FROM `__PREFIX__goods`) AND `id`!=" . $id .  "  AND `is_open`=1 LIMIT 4";
        $more_list = Db::query($sql);
        $this->assign('more_list',$more_list);

       return $this->fetch();
    }


}