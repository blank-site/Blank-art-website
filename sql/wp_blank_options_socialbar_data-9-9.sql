-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 09 月 10 日 02:44
-- 服务器版本: 5.1.37
-- PHP 版本: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `blank`
--

-- --------------------------------------------------------

--
-- 表的结构 `wp_blank_options`
--

CREATE TABLE IF NOT EXISTS `wp_blank_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL DEFAULT '0',
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1157 ;

--
-- 转存表中的数据 `wp_blank_options`
--

INSERT INTO `wp_blank_options` (`option_id`, `blog_id`, `option_name`, `option_value`, `autoload`) VALUES
(827, 0, 'widget_sh_sb_widget', 'a:2:{i:2;a:0:{}s:12:"_multiwidget";i:1;}', 'yes'),
(832, 0, 'sh_sb_items', 'a:6:{i:0;a:3:{s:5:"title";s:21:"follow us on facebook";s:4:"link";s:29:"http://facebook.com/luckliuyi";s:4:"icon";s:94:"http://localhost/blank/wp-content/plugins/social-bartender/images/sb-icons/social-facebook.png";}i:1;a:3:{s:5:"title";s:19:"follow us on flickr";s:4:"link";s:17:"http://flickr.com";s:4:"icon";s:92:"http://localhost/blank/wp-content/plugins/social-bartender/images/sb-icons/social-flickr.png";}i:2;a:3:{s:5:"title";s:21:"follow us on linkedin";s:4:"link";s:19:"http://linkedin.com";s:4:"icon";s:94:"http://localhost/blank/wp-content/plugins/social-bartender/images/sb-icons/social-linkedin.png";}i:3;a:3:{s:5:"title";s:7:"get rss";s:4:"link";s:11:"http://www.";s:4:"icon";s:89:"http://localhost/blank/wp-content/plugins/social-bartender/images/sb-icons/social-rss.png";}i:4;a:3:{s:5:"title";s:20:"follow us on twitter";s:4:"link";s:18:"http://twitter.com";s:4:"icon";s:93:"http://localhost/blank/wp-content/plugins/social-bartender/images/sb-icons/social-twitter.png";}i:5;a:3:{s:5:"title";s:5:"vimeo";s:4:"link";s:16:"http://vimeo.com";s:4:"icon";s:91:"http://localhost/blank/wp-content/plugins/social-bartender/images/sb-icons/social-vimeo.png";}}', 'yes'),
(908, 0, 'sh_sb_title_only', 'no', 'yes'),
(1020, 0, '_transient_plugins_delete_result_1', '1', 'yes');
