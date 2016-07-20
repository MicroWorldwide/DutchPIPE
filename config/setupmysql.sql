CREATE DATABASE `dutchpipe`;

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

