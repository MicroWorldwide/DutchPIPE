# Sets up a MySQL database and tables for DutchPIPE
#
# Import this file into MySQL when first installing DutchPIPE.
#
# DutchPIPE version 0.1; PHP version 5
#
# LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
# If you did not receive a copy of the DutchPIPE license, you can obtain one at
# http://dutchpipe.org/license/1_0.txt or by sending a note to
# license@dutchpipe.org, in which case you will be mailed a copy immediately.
#
# @package    DutchPIPE
# @subpackage config
# @author     Lennert Stock <ls@dutchpipe.org>
# @copyright  2006 Lennert Stock
# @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
# @version    Subversion: $Id: setupmysql.sql 73 2006-07-13 20:30:09Z ls $
# @link       http://dutchpipe.org/manual/package/DutchPIPE

CREATE DATABASE IF NOT EXISTS `dutchpipe`;

USE `dutchpipe`;

CREATE TABLE `Users` (
  `userId` int(11) NOT NULL auto_increment,
  `userUsername` varchar(32) NOT NULL,
  `userPassword` varchar(32) default NULL,
  `userCookieId` varchar(32) NOT NULL default '',
  `userCookiePassword` varchar(32) NOT NULL default '',
  `userUsernameLower` varchar(32) NOT NULL,
  PRIMARY KEY  (`userId`),
  KEY `userCookieId` (`userCookieId`,`userCookiePassword`)
);

CREATE TABLE `Captcha` (
  `captchaId` int(11) NOT NULL auto_increment,
  `captchaFile` varchar(32) NOT NULL,
  `captchaTimestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`captchaId`)
);

INSERT INTO `Captcha` VALUES ("1", "avyrjy.gif", now());

CREATE TABLE `UserAgents` (
  `userAgentId` int(11) NOT NULL auto_increment,
  `userAgentString` varchar(255) NOT NULL,
  `userAgentRemoteAddress` varchar(15) default NULL,
  PRIMARY KEY  (`userAgentId`),
  KEY `userAgentString` (`userAgentString`)
);

CREATE TABLE `UserAgentTitles` (
  `userAgentString` varchar(255) NOT NULL,
  `userAgentTitle` varchar(32) NOT NULL,
  PRIMARY KEY  (`userAgentString`)
);

INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)", "Googlebot");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)", "Yahoo! Slurp");
INSERT INTO `UserAgentTitles` VALUES ("Gigabot/2.0/gigablast.com/spider.html", "Gigabot");
INSERT INTO `UserAgentTitles` VALUES ("msnbot/0.9 (+http://search.msn.com/msnbot.htm)", "MSNBot");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://sp.ask.com/docs/about/tech_crawling.html)", "Ask Web Crawler");
INSERT INTO `UserAgentTitles` VALUES ("SurveyBot/2.3 (Whois Source)", "SurveyBot");
INSERT INTO `UserAgentTitles` VALUES ("ichiro/2.0 (http://help.goo.ne.jp/door/crawler.html)", "Ichiro Web Crawler");

