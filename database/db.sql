-- MySQL dump 10.13  Distrib 5.7.12, for Linux (x86_64)
--
-- Host: localhost    Database: netexam
-- ------------------------------------------------------
-- Server version	5.7.12-0ubuntu1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `exam`
--

DROP TABLE IF EXISTS `exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `effect_time` datetime NOT NULL COMMENT '生效时间',
  `file_name` varchar(256) NOT NULL COMMENT '对应的上传文件路径',
  `question_count` int(11) NOT NULL COMMENT '问题数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='考试排期与考题文件';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) NOT NULL COMMENT '考试ID, 参照exam表',
  `qid` int(11) NOT NULL,
  `question` varchar(1024) NOT NULL COMMENT '题干描述',
  `A` varchar(512) NOT NULL COMMENT 'A-J为选项',
  `B` varchar(512) NOT NULL,
  `C` varchar(512) DEFAULT NULL,
  `D` varchar(512) DEFAULT NULL,
  `E` varchar(512) DEFAULT NULL,
  `F` varchar(512) DEFAULT NULL,
  `G` varchar(512) DEFAULT NULL,
  `H` varchar(512) DEFAULT NULL,
  `I` varchar(512) DEFAULT NULL,
  `J` varchar(512) DEFAULT NULL,
  `answer` varchar(11) NOT NULL COMMENT '正确答案',
  `score` int(11) NOT NULL COMMENT '本题得分',
  `timer` int(11) NOT NULL COMMENT '回答问题倒计时',
  `keep_time` int(11) NOT NULL COMMENT '显示正确答案后继续停留在当前页面的时间',
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='题目表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_00`
--

DROP TABLE IF EXISTS `user_00`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_00` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_01`
--

DROP TABLE IF EXISTS `user_01`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_01` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_02`
--

DROP TABLE IF EXISTS `user_02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_02` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_03`
--

DROP TABLE IF EXISTS `user_03`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_03` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_04`
--

DROP TABLE IF EXISTS `user_04`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_04` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_05`
--

DROP TABLE IF EXISTS `user_05`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_05` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_06`
--

DROP TABLE IF EXISTS `user_06`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_06` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_07`
--

DROP TABLE IF EXISTS `user_07`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_07` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID 唯一,手机号码',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  sha1加密',
  `passwd_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '密码强度 0未评级 1低 2中 3强',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `type` tinyint(3) NOT NULL COMMENT '登陆类型',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户认证表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_admin`
--

DROP TABLE IF EXISTS `user_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_admin` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID 唯一',
  `user_name` varchar(20) NOT NULL COMMENT '用户名 唯一',
  `passwd` varchar(40) NOT NULL COMMENT '用户密码  md5加密',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆IP',
  `is_login` enum('0','1') NOT NULL DEFAULT '1' COMMENT '是否允许用户登陆 默认值1 允许',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理账号表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_00`
--

DROP TABLE IF EXISTS `user_answer_00`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_00` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_01`
--

DROP TABLE IF EXISTS `user_answer_01`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_01` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_02`
--

DROP TABLE IF EXISTS `user_answer_02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_02` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_03`
--

DROP TABLE IF EXISTS `user_answer_03`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_03` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_04`
--

DROP TABLE IF EXISTS `user_answer_04`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_04` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_05`
--

DROP TABLE IF EXISTS `user_answer_05`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_05` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_06`
--

DROP TABLE IF EXISTS `user_answer_06`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_06` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_answer_07`
--

DROP TABLE IF EXISTS `user_answer_07`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_answer_07` (
  `user_id` bigint(20) NOT NULL,
  `question_id` int(11) NOT NULL COMMENT '问题ID',
  `answer` varchar(11) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户回答',
  `score` int(11) DEFAULT '0' COMMENT '用户本题得分',
  `create_time` int(11) NOT NULL COMMENT '本条创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_00`
--

DROP TABLE IF EXISTS `user_details_00`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_00` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_01`
--

DROP TABLE IF EXISTS `user_details_01`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_01` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_02`
--

DROP TABLE IF EXISTS `user_details_02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_02` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_03`
--

DROP TABLE IF EXISTS `user_details_03`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_03` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_04`
--

DROP TABLE IF EXISTS `user_details_04`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_04` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_05`
--

DROP TABLE IF EXISTS `user_details_05`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_05` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_06`
--

DROP TABLE IF EXISTS `user_details_06`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_06` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_details_07`
--

DROP TABLE IF EXISTS `user_details_07`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_details_07` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT '用户ID 唯一,手机号码',
  `reg_ip` int(10) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('0','1') DEFAULT NULL COMMENT '性别 NULL 未知, 0=女, 1=男',
  `marital` tinyint(1) NOT NULL DEFAULT '0' COMMENT '婚姻状况:未婚0已婚1离婚3',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实名字',
  `face` varchar(20) NOT NULL COMMENT '头像',
  `birthday` date NOT NULL DEFAULT '1970-01-01' COMMENT '生日',
  `signature` text NOT NULL COMMENT '个性签名',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '详细地址',
  `country` int(11) NOT NULL DEFAULT '0',
  `province` int(11) NOT NULL DEFAULT '0',
  `city` int(11) NOT NULL DEFAULT '0',
  `points` int(10) NOT NULL COMMENT '充值积分',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户注册详细信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_00`
--

DROP TABLE IF EXISTS `user_exam_00`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_00` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_01`
--

DROP TABLE IF EXISTS `user_exam_01`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_01` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_02`
--

DROP TABLE IF EXISTS `user_exam_02`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_02` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_03`
--

DROP TABLE IF EXISTS `user_exam_03`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_03` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_04`
--

DROP TABLE IF EXISTS `user_exam_04`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_04` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_05`
--

DROP TABLE IF EXISTS `user_exam_05`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_05` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_06`
--

DROP TABLE IF EXISTS `user_exam_06`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_06` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_exam_07`
--

DROP TABLE IF EXISTS `user_exam_07`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_exam_07` (
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `eid` int(11) NOT NULL COMMENT '考试排期',
  `score` int(11) NOT NULL DEFAULT '0' COMMENT '本期得分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-07-02 23:03:11
