# Sets up a MySQL database and tables for DutchPIPE
#
# Import this file into MySQL when first installing DutchPIPE.
#
# DutchPIPE version 0.3; PHP version 5
#
# LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
# If you did not receive a copy of the DutchPIPE license, you can obtain one at
# http://dutchpipe.org/license/1_0.txt or by sending a note to
# license@dutchpipe.org, in which case you will be mailed a copy immediately.
#
# @package    DutchPIPE
# @subpackage config
# @author     Lennert Stock <ls@dutchpipe.org>
# @copyright  2006, 2007 Lennert Stock
# @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
# @version    Subversion: $Id: setupmysql.sql 252 2007-08-02 23:30:58Z ls $
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
  `userAvatarNr` tinyint(3) unsigned NOT NULL default '1',
  `userAge` int(11) unsigned NOT NULL default '0',
  `userDisplayMode` varchar(32) NOT NULL default 'graphical',
  `userEventPeopleLeaving` enum('0','1') NOT NULL default '0',
  `userEventPeopleEntering` enum('0','1') NOT NULL default '0',
  `userEventBotsEntering` enum('0','1') NOT NULL default '0',
  `userHomeLocation` varchar(128) default NULL,
  `userHomeSublocation` varchar(128) default NULL,
  `userInputMode` enum('say','cmd') default 'say',
  `userInputEnabled` enum('off', 'on') default 'off',
  `userInputPersistent` enum('once','page','always') NOT NULL default 'page',
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
  `userAgentTitle` varchar(64) default NULL,
  PRIMARY KEY  (`userAgentString`)
);

INSERT INTO `UserAgentTitles` VALUES ("Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)", "Ask Crawler");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)", "Ask Crawler");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://sp.ask.com/docs/about/tech_crawling.html)", "Ask Crawler");
INSERT INTO `UserAgentTitles` VALUES ("Baiduspider+(+http://www.baidu.com/search/spider_jp.html)", "Baiduspider");
INSERT INTO `UserAgentTitles` VALUES ("Baiduspider+(+http://www.baidu.com/search/spider.htm)", "Baiduspider");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; BecomeBot/3.0; +http://www.become.com/site_owners.html)", "BecomeBot");
INSERT INTO `UserAgentTitles` VALUES ("Speedy Spider (Entireweb; Beta/1.2; http://www.entireweb.com/about/search_tech/speedyspider/)", "Entireweb Speedy Spider");
INSERT INTO `UserAgentTitles` VALUES ("Speedy Spider (http://www.entireweb.com/about/search_tech/speedyspider/)", "Entireweb Speedy Spider");
INSERT INTO `UserAgentTitles` VALUES ("Gigabot/2.0/gigablast.com/spider.html", "Gigabot");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)", "Googlebot");
INSERT INTO `UserAgentTitles` VALUES ("ichiro/2.0 (http://help.goo.ne.jp/door/crawler.html)", "Ichiro Web Crawler");
INSERT INTO `UserAgentTitles` VALUES ("ilial/Nutch-0.9 (Ilial, Inc. is a Los Angeles based Internet startup company.; http://www.ilial.com/crawler; crawl@ilial.com)", "Ilial crawler");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; heritrix/1.10.2 +http://i.stanford.edu/)", "Internet Archive Heritrix Crawler");
INSERT INTO `UserAgentTitles` VALUES ("IRLbot/3.0 (compatible; MSIE 6.0; http://irl.cs.tamu.edu/crawler)", "IRL crawler");
INSERT INTO `UserAgentTitles` VALUES ("Krugle/Krugle,Nutch/0.8+ (Krugle web crawler; http://corp.krugle.com/crawler/info.html; webcrawler@krugle.com)", "Krugle Crawler");
INSERT INTO `UserAgentTitles` VALUES ("msnbot/1.0 (+http://search.msn.com/msnbot.htm)", "MSNBot");
INSERT INTO `UserAgentTitles` VALUES ("msnbot-media/1.0 (+http://search.msn.com/msnbot.htm)", "MSNBot");
INSERT INTO `UserAgentTitles` VALUES ("MSRBOT (http://research.microsoft.com/research/sv/msrbot)", "MSRBot");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/4.0 (compatible; NaverBot/1.0; http://help.naver.com/delete_main.asp)", "NaverBot");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/4.0 (compatible; Netcraft Web Server Survey)", "Netcraft Survey Bot");
INSERT INTO `UserAgentTitles` VALUES ("psbot/0.1 (+http://www.picsearch.com/bot.html)", "Psbot (picsearch.com)");
INSERT INTO `UserAgentTitles` VALUES ("Sogou web spider/3.0(+http://www.sogou.com/docs/help/webmasters.htm#07)", "Sogou Spider");
INSERT INTO `UserAgentTitles` VALUES ("SurveyBot/2.3 (Whois Source)", "SurveyBot");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)", "Yahoo! Slurp");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; Yahoo! Slurp China; http://misc.yahoo.com.cn/help.html)", "Yahoo! Slurp China");
INSERT INTO `UserAgentTitles` VALUES ("Mozilla/5.0 (compatible; YodaoBot/1.0; http://www.yodao.com/help/webmaster/spider/; )", "YodaoBot");
