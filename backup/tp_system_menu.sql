 CREATE TABLE `tp_system_menu` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '权限名字',
  `group` varchar(20) DEFAULT NULL COMMENT '所属分组',
  `right` text COMMENT '权限码(控制器+动作)',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '删除状态 1删除,0正常',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

INSERT INTO `tp_system_menu` VALUES (1, '网站设置', 'system', 'System@index,System@handle', 0);
INSERT INTO `tp_system_menu` VALUES (2, '权限资源', 'system', 'System@right_list,System@edit_right,System@right_del,System@ajax_get_action', 0);
INSERT INTO `tp_system_menu` VALUES (3, '前台导航设置', 'system', 'System@navigationList,System@addEditNav,System@delNav', 0);
INSERT INTO `tp_system_menu` VALUES (4, '管理员管理', 'system', 'Admin@index,Admin@admin_info,Admin@adminHandle', 0);
INSERT INTO `tp_system_menu` VALUES (5, '角色管理', 'system', 'Admin@role,Admin@role_info,Admin@roleSave,Admin@roleDel', 0);
INSERT INTO `tp_system_menu` VALUES (6, '商品编辑', 'goods', 'Goods@addEditGoods,Goods@delGoods,Goods@del_goods_images', 0);
INSERT INTO `tp_system_menu` VALUES (7, '商品列表', 'goods', 'Goods@goodsList,Goods@ajaxGoodsList,Goods@updateField', 0);
INSERT INTO `tp_system_menu` VALUES (8, '商品分类', 'goods', 'Goods@categoryList,Goods@addEditCategory,Goods@delGoodsCategory', 0);
INSERT INTO `tp_system_menu` VALUES (9, '商品品牌', 'goods', 'Goods@brandList,Goods@delBrand,Goods@addEditBrand', 0);
INSERT INTO `tp_system_menu` VALUES (10, '轮播列表', 'content', 'Banner@bannerHandle,Banner@bannerList,Banner@banner,Banner@editBanner', 0);
INSERT INTO `tp_system_menu` VALUES (11, '轮播位', 'content', 'Banner@positionHandle,Banner@positionList,Banner@position', 0);
INSERT INTO `tp_system_menu` VALUES (12, '活动管理', 'marketing', 'Activity@activityList,Activity@activity,Activity@activity_save,Activity@activity_del', 0);
INSERT INTO `tp_system_menu` VALUES (13, '数据备份', 'tools', 'Tools@index,Tools@optimize,Tools@repair,Tools@export', 0);
INSERT INTO `tp_system_menu` VALUES (14, '数据还原', 'tools', 'Tools@restore,Tools@downFile,Tools@del,Tools@import', 0);
INSERT INTO `tp_system_menu` VALUES (15, '地区管理', 'tools', 'Tools@region,Tools@regionHandle', 0);
INSERT INTO `tp_system_menu` VALUES (16, '模板管理', 'system', 'Template@templateList,Template@changeTemplate', 0);
INSERT INTO `tp_system_menu` VALUES (17, '操作日志', 'system', 'Admin@log', 0);
INSERT INTO `tp_system_menu` VALUES (18, '友情链接', 'content', 'Article@link,Article@linkList,Article@linkHandle', 0);
INSERT INTO `tp_system_menu` VALUES (19, '刷新缓存', 'system', 'System@cleanCache', 0);
INSERT INTO `tp_system_menu` VALUES (20, '修改管理员密码', 'system', 'Admin@modify_pwd', 0);
