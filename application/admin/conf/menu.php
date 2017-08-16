<?php
return	array(	
	'system'=>array('name'=>'网站配置','child'=>array(
				array('name' => '设置','child' => array(
						array('name'=>'站点设置','act'=>'index','op'=>'System'),
						array('name'=>'地区设置','act'=>'region','op'=>'Tools'),
						array('name'=>'导航设置','act'=>'navigationList','op'=>'System'),
						array('name'=>'友情链接','act'=>'linkList','op'=>'Article'),
						array('name'=>'清除缓存','act'=>'cleanCache','op'=>'System'),
				)),
				array('name' => '数据','child'=>array(
						array('name' => '数据备份', 'act'=>'index', 'op'=>'Tools'),
						array('name' => '数据还原', 'act'=>'restore', 'op'=>'Tools'),
				)),
				array('name' => '轮播','child' => array(
						array('name'=>'轮播列表','act'=>'bannerList','op'=>'Banner'),
						array('name'=>'轮播位置','act'=>'positionList','op'=>'Banner'),
				)),
				array('name' => '文章','child'=>array(
						array('name' => '文章列表', 'act'=>'articleList', 'op'=>'Article'),
						array('name' => '文章分类', 'act'=>'categoryList', 'op'=>'Article'),
				)),
				array('name' => '权限','child'=>array(
						array('name' => '管理员列表', 'act'=>'index', 'op'=>'Admin'),
						array('name' => '角色管理', 'act'=>'role', 'op'=>'Admin'),
						array('name'=>'权限资源列表','act'=>'right_list','op'=>'System'),
						array('name' => '管理员日志', 'act'=>'log', 'op'=>'Admin'),
				)),
			
				array('name' => '模板','child'=>array(
						array('name' => '模板设置', 'act'=>'templateList', 'op'=>'Template'),
				)),

	)),
		
	'shop'=>array('name'=>'君太百货','child'=>array(
				array('name' => '商品','child' => array(
					array('name' => '商品分类', 'act'=>'categoryList', 'op'=>'Goods'),
					array('name' => '商品列表', 'act'=>'goodsList', 'op'=>'Goods'),
					array('name' => '品牌列表', 'act'=>'brandList', 'op'=>'Goods'),
			)),
			array('name' => '活动','child' => array(
					array('name' => '最新活动', 'act'=>'activityList', 'op'=>'Activity'),
			)),
	)),
);