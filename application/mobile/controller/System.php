<?php
/**
 * ============================================================================
 * Created by PhpStorm.
 * User: dd
 * Date: 2017/8/31
 * Time: 18:13
 * ============================================================================
 * $Author:lieyan123091
 */


namespace app\mobile\controller;


use think\Request;
use think\Db;

class System extends Base
{
    /**
     * 关于君太
     * @return mixed
     */
    public function about()
    {
        //全屏banner
        $banner_list = S('Pos:system:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%关于君太页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:system:banner_list',$banner_list,JT_CACHE_TIME);
        }

        $info = Db::name('web_static')->where('pid',1)->cache(true,JT_CACHE_TIME)->select();
        $title = $content = $img = [];
        foreach($info as $v){
            $title[$v['name']] = $v['title'];
            $content[$v['name']] = $v['content'];
            $img[$v['name']] = $v['img'];
        }
        $this->assign('img',$img);
        $this->assign('content',$content);
        $this->assign('title',$title);

        $position = Request::instance()->controller();
        $this->assign('position',$position);
        $this->assign('banner_list',$banner_list);
        return $this->fetch();
    }

    /**
     * 版权声明
     */
    public function copyright()
    {
        $content = Db::name('web_static')->field('content')->where('id',3)->cache(true,JT_CACHE_TIME)->find();
        $this->assign('content',$content);
        $position = Request::instance()->controller();
        $this->assign('position',$position);
        return $this->fetch();
    }
}