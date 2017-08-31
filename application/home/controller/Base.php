<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * $Author: IT宇宙人 2015-08-10 $
 */ 
namespace app\home\controller;
use think\Controller;
use think\Db;
use think\Session;

class Base extends Controller {
    public $session_id;
    /*
     * 初始化操作
     */
    public function _initialize() {
        Session::start();
        header("Cache-control: private");  // history.back返回后输入框值丢失问题 参考文章 http://www.tp-shop.cn/article_id_1465.html  http://blog.csdn.net/qinchaoguang123456/article/details/29852881
    	$this->session_id = session_id(); // 当前的 session_id
        define('SESSION_ID',$this->session_id); //将当前的session_id保存为常量，供其它方法调用
        $this->public_assign();
    }
    /**
     * 保存公告变量到 smarty中 比如 导航 
     */
    public function public_assign()
    {
       $jt_config = $jt_navigation =  array();
       $jt_config = M('config')->cache(true,JT_CACHE_TIME)->select();
       foreach($jt_config as $k => $v)
       {
          $tpshop_config[$v['inc_type'].'_'.$v['name']] = $v['value'];
       }
       $jt_navigation = Db::name('navigation')->cache(true,JT_CACHE_TIME)->where('is_show',1)->order('sort')->select();
       $this->assign('jt_navigation',$jt_navigation);
       $this->assign('jt_config', $jt_config);
    }

    public function ajaxReturn($data)
    {
        exit(json_encode($data));
    }
}