<?php
/**
 * ============================================================================
 * Created by PhpStorm.
 * User: dd
 * Date: 2017/9/1
 * Time: 14:55
 * ============================================================================
 * $Author:lieyan123091
 */


namespace app\mobile\controller;
use think\Request;
use think\Db;
class Vip extends Base
{
    /**
     * 君太VIP
     * @return mixed
     */
    public function index()
    {
        //全屏banner
        $banner_list = S('Pos:vip:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%君太VIP页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:vip:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list', $banner_list);

        $info = Db::name('web_static')->where('id',2)->whereOr('pid',2)->select();
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
        return $this->fetch();
    }
}