/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : casper_db

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-11-20 19:24:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `found`
-- ----------------------------
DROP TABLE IF EXISTS `found`;
CREATE TABLE `found` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `data` varchar(40) DEFAULT NULL,
  `fsid` int(11) DEFAULT NULL,
  PRIMARY KEY (`fid`),
  KEY `fsid` (`fsid`),
  CONSTRAINT `fsid` FOREIGN KEY (`fsid`) REFERENCES `fscans` (`fsid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of found
-- ----------------------------

-- ----------------------------
-- Table structure for `fscans`
-- ----------------------------
DROP TABLE IF EXISTS `fscans`;
CREATE TABLE `fscans` (
  `fsid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `filename` char(30) NOT NULL,
  `scantype` tinyint(1) NOT NULL,
  `date_scan` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`fsid`),
  KEY `tid` (`tid`),
  CONSTRAINT `tid` FOREIGN KEY (`tid`) REFERENCES `targets` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of fscans
-- ----------------------------

-- ----------------------------
-- Table structure for `servers`
-- ----------------------------
DROP TABLE IF EXISTS `servers`;
CREATE TABLE `servers` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `url` char(50) NOT NULL,
  `status` tinyint(1) DEFAULT '-1',
  `date_add` datetime NOT NULL,
  `date_refresh` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ip` char(16) DEFAULT '-1',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of servers
-- ----------------------------
INSERT INTO `servers` VALUES ('21', 'http://google.com/', '0', '0000-00-00 00:00:00', '2015-10-19 15:10:30', '-1');
INSERT INTO `servers` VALUES ('78', 'sorvikupon.ru', '0', '2015-11-02 08:46:19', '2015-11-02 08:46:25', '-1');
INSERT INTO `servers` VALUES ('112', 'go2ogle.com', '-1', '2015-11-02 14:25:29', '0000-00-00 00:00:00', '-1');
INSERT INTO `servers` VALUES ('114', 'vvv.com', '-1', '2015-11-02 14:29:04', '0000-00-00 00:00:00', '-1');
INSERT INTO `servers` VALUES ('115', 'sdsdfdsf.ru', '-1', '2015-11-19 15:56:10', '0000-00-00 00:00:00', '-1');

-- ----------------------------
-- Table structure for `targets`
-- ----------------------------
DROP TABLE IF EXISTS `targets`;
CREATE TABLE `targets` (
  `tid` int(11) NOT NULL,
  `url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ip` char(20) CHARACTER SET latin1 NOT NULL,
  `date_add` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `pid` int(11) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of targets
-- ----------------------------
INSERT INTO `targets` VALUES ('0', 'http://qwer.ru/', '1.1.1.1', '2015-11-12 14:57:40', null);
INSERT INTO `targets` VALUES ('1', 'http://qwer.com/', '2.2.2.2', '0000-00-00 00:00:00', null);

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` char(50) CHARACTER SET utf8 NOT NULL,
  `password` char(50) CHARACTER SET utf8 NOT NULL,
  `last_login` datetime NOT NULL,
  `last_ip` char(20) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'admin', 'cfcd208495d565ef66e7dff9f98764da', '2015-11-19 11:08:59', '127.0.0.1');
