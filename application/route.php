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
    'latest' => 'activity/latest'
];

