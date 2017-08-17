CREATE TABLE `tp_admin` (
  `admin_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_name` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `email` varchar(60) NOT NULL DEFAULT '' COMMENT 'email',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `ec_salt` varchar(10) DEFAULT NULL COMMENT '秘钥',
  `add_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `last_login` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `role_id` smallint(5) DEFAULT '0' COMMENT '角色id',
  PRIMARY KEY (`admin_id`),
  KEY `user_name` (`user_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `tp_admin` VALUES (1, 'admin', '1766266374@qq.com', '90600d68b0f56d90c4c34284d8dfd138', '', 1499673525, 1502962539, '127.0.0.1', 1);
INSERT INTO `tp_admin` VALUES (8, 'dumin', '1234563444@qq.com', '90600d68b0f56d90c4c34284d8dfd138', '', 1502952258, 1502961921, '127.0.0.1', 2);
INSERT INTO `tp_admin` VALUES (9, 'lieyan', '1149501742@qq.com', '96e03d661deb0146a37504175d12bae6', '', 1502959494, 1502959515, '127.0.0.1', 9);
