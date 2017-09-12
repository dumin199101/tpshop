<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//
//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//    'goodsInfo/[:id]' => ['Home/Goods/goodsInfo',['method' => 'get', 'ext' => 'html'],'cache'=>3600]
//    //Home/Goods/goodsInfo/id/104.html
//];
//use think\Route;
// 注册路由到index模块的News控制器的read操作
//Route::get('goodsInfo/:id','home/goods/goodsInfo',['cache'=>['Home/Goods/goodsInfo',300]]);// 访问方式 http://www.tpshop2.0.com/goodsInfo/77.html

// http://www.tpshop2.0.com/home/goods/goodsInfo/id/77.html

return [
    //Web端
    '[g]'     => [
        ':id'   => ['goods/goodsInfo', ['method' => 'get'],['id' => '\d+']],
    ],
    '[p]'     => [
        ':id'   => ['goods/personInfo', ['method' => 'get'],['id' => '\d+']],
    ],
    '[act]'     => [
        ':id'   => ['activity/info', ['method' => 'get'],['id' => '\d+']],
    ],
    '[filterByLetter]'     => [
        ':letter'   => ['brand/index', ['method' => 'get'],['letter' => '[A-Z]']],
    ],
    'activity' => 'activity/index',
    'brand' => 'brand/index',
    'goods' => 'goods/index',
    'vip' => 'vip/index',
    'about' => 'system/about',
    'copyright' => 'system/copyright',
    'personList' => 'goods/personList',
    'goodsList' => 'goods/goodsList',
    'oldest' => 'activity/oldest',
    'latest' => 'activity/latest',
    'index'=>'index/index',

    //移动端

    '[m-g]'     => [
        ':id'   => ['mobile/goods/goodsInfo', ['method' => 'get'],['id' => '\d+']],
    ],
    '[m-p]'     => [
        ':id'   => ['mobile/goods/personInfo', ['method' => 'get'],['id' => '\d+']],
    ],
    '[m-act]'     => [
        ':id'   => ['mobile/activity/info', ['method' => 'get'],['id' => '\d+']],
    ],
    '[m-filterByLetter]'     => [
        ':letter'   => ['mobile/brand/index', ['method' => 'get'],['letter' => '[A-Z]']],
    ],
    'm-activity' => 'mobile/activity/index',
    'm-brand' => 'mobile/brand/index',
    'm-goods' => 'mobile/goods/index',
    'm-vip' => 'mobile/vip/index',
    'm-about' => 'mobile/system/about',
    'm-copyright' => 'mobile/system/copyright',
    'm-personList' => 'mobile/goods/personList',
    'm-goodsList' => 'mobile/goods/goodsList',
    'm-oldest' => 'mobile/activity/oldest',
    'm-latest' => 'mobile/activity/latest',
    'm-index'=>'mobile/index/index',


];

