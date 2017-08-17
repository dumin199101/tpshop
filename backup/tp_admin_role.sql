CREATE TABLE `tp_admin_role` (
  `role_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(30) DEFAULT NULL COMMENT '角色名称',
  `act_list` text COMMENT '权限列表',
  `role_desc` varchar(255) DEFAULT NULL COMMENT '角色描述',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO `tp_admin_role` VALUES (1, '超级管理员', 'all', '管理全站');
INSERT INTO `tp_admin_role` VALUES (2, '网站编辑', '1,3,16,6,7,8,9,10,11,18,12', '负责发布更新网站信息');
INSERT INTO `tp_admin_role` VALUES (9, '网站运维', '1,2,4,5,17,19,20,13,14,15', '负责维护网站正常运转');
INSERT INTO `tp_admin_role` VALUES (10, '网站管理员', '1,2,3,4,5,16,17,19,20,13,14,15', '拥有仅次于超级管理员的权限');
