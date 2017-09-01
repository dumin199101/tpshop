<?php
/**
 * Created by PhpStorm.
 * User: dd
 * Date: 2017/8/31
 * Time: 9:30
 */

namespace app\home\controller;

use think\Db;
use think\Request;

class Brand extends Base
{
    /**
     * 品牌索引首页
     * @return mixed
     */
    public function index(){
        //全屏banner
        $banner_list = S('Pos:brand:banner_list');
        if(empty($banner_list))
        {
            $banner_list = Db::name('banner')->alias('a')
                ->field('a.banner_link,a.banner_code,a.target')
                ->join('__BANNER_POSITION__ b','a.pid=b.position_id')
                ->where('a.enabled',1)
                ->where('a.start_time','<',time())
                ->where('a.end_time','>',time())
                ->where('b.position_name','like','%品牌页%')
                ->order('a.orderby desc')
                ->select();
            S('Pos:brand:banner_list',$banner_list,JT_CACHE_TIME);
        }
        $this->assign('banner_list',$banner_list);

        //BRAND_品牌
        $letter_list = range('A','Z');
        $this->assign('letter_list',$letter_list);

        //接收请求参数：
        $letter = input('param.letter/s','');
        if(empty($letter)){
            //获取所有品牌
            $brand_list = Db::name('brand')->field('id,logo')
                ->order('sort')
                ->cache(true,JT_CACHE_TIME)
                ->select();
        }else{
            //获取当前字母下的品牌
            $brand_list = Db::name('brand')->field('id,logo')
                ->where('letter',$letter)
                ->order('sort')
                ->cache(true,JT_CACHE_TIME)
                ->select();
        }
        $this->assign('letter',$letter);
        $this->assign('brand_list',$brand_list);
        $position = Request::instance()->controller();
        $this->assign('position',$position);
        return $this->fetch();
    }
}