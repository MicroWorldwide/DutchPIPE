#!/usr/local/bin/php -q
<?php
/**
 * Creates DutchPIPE database and tables
 *
 * DutchPIPE version 0.4; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage scripts
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: setupdb.php 293 2007-08-25 23:11:20Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php
 */

/**
 * Gets server and universe settings
 */
require_once(realpath(dirname(__FILE__) . '/..') . '/config/dpserver-ini.php');
require_once(DPSERVER_DPUNIVERSE_CONFIG_PATH . 'dpuniverse-ini.php');

error_reporting(E_ALL);

echo "\n===> DutchPIPE database setup started, setup type: "
    . (!DPUNIVERSE_MDB2_ENABLED ? 'MySQL without MDB2'
    : $DPUNIVERSE_MDB2_DSN['phptype'] . ' using MDB2') . "\n";

$setupResult = !DPUNIVERSE_MDB2_ENABLED ? setup_mysql() : setup_mdb2();

echo '===> DutchPIPE database setup '
    . (TRUE !== $setupResult ? 'aborted' : 'completed successfully') . "\n\n";

function setup_mysql()
{
    echo '===> Connecting to database server... ';

    $connection = @mysql_connect(DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER,
        DPUNIVERSE_MYSQL_PASSWORD);
    if (!is_resource($connection)) {
        echo "FAILED

     The MySQL server reported the following error [error number "
            . mysql_errno() . "]:
     \"" . mysql_error() . "\"

     Failed to connect to the database server. This is probably because of
     invalid DPUNIVERSE_MYSQL_* constants in dpuniverse-ini.php or insufficient
     permissions of your database user. Please check if the DPUNIVERSE_MYSQL_*
     constants are correct.\n\n";
        return FALSE;
    }

    echo "OK\n===> Selecting database \"" . DPUNIVERSE_MYSQL_DB . '"... ';

    if (!mysql_select_db(DPUNIVERSE_MYSQL_DB)) {
        echo "FAILED\n     No permission or database \"" . DPUNIVERSE_MYSQL_DB
            . "\" does not exist\n===> Attempting to create database \""
            . DPUNIVERSE_MYSQL_DB . "\"... ";
        if (!@mysql_query("CREATE DATABASE IF NOT EXISTS `"
                . DPUNIVERSE_MYSQL_DB. "`")
                || !mysql_select_db(DPUNIVERSE_MYSQL_DB)) {
            echo "FAILED

     The MySQL server reported the following error [error number "
        . mysql_errno() . "]:
     \"" . mysql_error() . "\"

     Failed to select or create database \"" . DPUNIVERSE_MYSQL_DB . "\". This is probably
     because of invalid DPUNIVERSE_MYSQL_* constants in dpuniverse-ini.php or
     insufficient permissions of your database user (and good security!). Please
     check if the DPUNIVERSE_MYSQL_* constants are correct. If needed, create a
     database called \"" . DPUNIVERSE_MYSQL_DB . "\" manually and/or make sure your database user can
     select it, then run this script again.\n\n";
            return FALSE;
        }
        echo "OK\n===> Selecting database \"" . DPUNIVERSE_MYSQL_DB
            . "\"... OK\n";

    } else {
        echo "OK\n";
    }

    $sql_setup = array(
        "CREATE TABLE `Users` (`userId` int(11) UNSIGNED NOT NULL auto_increment,`userUsername` varchar(32) NOT NULL,`userPassword` varchar(32) default NULL,`userCookieId` varchar(32) NOT NULL default '',`userCookiePassword` varchar(32) NOT NULL default '',`userUsernameLower` varchar(32) NOT NULL,`userAvatarNr` tinyint(3) UNSIGNED NOT NULL default '1',`userAvatarCustom` varchar(9) default NULL,`userAge` int(11) UNSIGNED NOT NULL default '0',`userDisplayMode` varchar(32) NOT NULL default 'graphical',`userEventPeopleLeaving` enum('0','1') NOT NULL default '0',`userEventPeopleEntering` enum('0','1') NOT NULL default '0',`userEventBotsEntering` enum('0','1') NOT NULL default '0',`userHomeLocation` varchar(128) default NULL,`userHomeSublocation` varchar(128) default NULL,`userInputMode` enum('say','cmd') default 'say',`userInputEnabled` enum('off','on') default 'off',`userInputPersistent` enum('once','page','always') NOT NULL default 'page',PRIMARY KEY  (`userId`),KEY `userCookieId` (`userCookieId`,`userCookiePassword`)) DEFAULT CHARSET=utf8",
        "CREATE TABLE `Captcha` (`captchaId` int(11) UNSIGNED NOT NULL auto_increment,`captchaFile` varchar(32) NOT NULL,`captchaTimestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,PRIMARY KEY  (`captchaId`)) DEFAULT CHARSET=utf8",
        "INSERT INTO `Captcha` VALUES ('1', 'avyrjy.gif', now())",
        "CREATE TABLE `UserAgents` (`userAgentId` int(11) UNSIGNED NOT NULL auto_increment,`userAgentString` varchar(255) NOT NULL,`userAgentRemoteAddress` varchar(15) default NULL,PRIMARY KEY  (`userAgentId`),KEY `userAgentString` (`userAgentString`)) DEFAULT CHARSET=utf8",
        "CREATE TABLE `UserAgentTitles` (`userAgentString` varchar(255) NOT NULL,`userAgentTitle` varchar(64) default NULL,PRIMARY KEY  (`userAgentString`)) DEFAULT CHARSET=utf8",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)', 'Ask Crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/5.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)', 'Ask Crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://sp.ask.com/docs/about/tech_crawling.html)', 'Ask Crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('Baiduspider+(+http://www.baidu.com/search/spider_jp.html)', 'Baiduspider')",
        "INSERT INTO `UserAgentTitles` VALUES ('Baiduspider+(+http://www.baidu.com/search/spider.htm)', 'Baiduspider')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/5.0 (compatible; BecomeBot/3.0; +http://www.become.com/site_owners.html)', 'BecomeBot')",
        "INSERT INTO `UserAgentTitles` VALUES ('Speedy Spider (Entireweb; Beta/1.2; http://www.entireweb.com/about/search_tech/speedyspider/)', 'Entireweb Speedy Spider')",
        "INSERT INTO `UserAgentTitles` VALUES ('Speedy Spider (http://www.entireweb.com/about/search_tech/speedyspider/)', 'Entireweb Speedy Spider')",
        "INSERT INTO `UserAgentTitles` VALUES ('Gigabot/2.0/gigablast.com/spider.html', 'Gigabot')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'Googlebot')",
        "INSERT INTO `UserAgentTitles` VALUES ('ichiro/2.0 (http://help.goo.ne.jp/door/crawler.html)', 'Ichiro Web Crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('ilial/Nutch-0.9 (Ilial, Inc. is a Los Angeles based Internet startup company.; http://www.ilial.com/crawler; crawl@ilial.com)', 'Ilial crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/5.0 (compatible; heritrix/1.10.2 +http://i.stanford.edu/)', 'Internet Archive Heritrix Crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('IRLbot/3.0 (compatible; MSIE 6.0; http://irl.cs.tamu.edu/crawler)', 'IRL crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('Krugle/Krugle,Nutch/0.8+ (Krugle web crawler; http://corp.krugle.com/crawler/info.html; webcrawler@krugle.com)', 'Krugle Crawler')",
        "INSERT INTO `UserAgentTitles` VALUES ('msnbot/1.0 (+http://search.msn.com/msnbot.htm)', 'MSNBot')",
        "INSERT INTO `UserAgentTitles` VALUES ('msnbot-media/1.0 (+http://search.msn.com/msnbot.htm)', 'MSNBot')",
        "INSERT INTO `UserAgentTitles` VALUES ('MSRBOT (http://research.microsoft.com/research/sv/msrbot)', 'MSRBot')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/4.0 (compatible; NaverBot/1.0; http://help.naver.com/delete_main.asp)', 'NaverBot')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/4.0 (compatible; Netcraft Web Server Survey)', 'Netcraft Survey Bot')",
        "INSERT INTO `UserAgentTitles` VALUES ('psbot/0.1 (+http://www.picsearch.com/bot.html)', 'Psbot (picsearch.com)')",
        "INSERT INTO `UserAgentTitles` VALUES ('Sogou web spider/3.0(+http://www.sogou.com/docs/help/webmasters.htm#07)', 'Sogou Spider')",
        "INSERT INTO `UserAgentTitles` VALUES ('SurveyBot/2.3 (Whois Source)', 'SurveyBot')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', 'Yahoo! Slurp')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/5.0 (compatible; Yahoo! Slurp China; http://misc.yahoo.com.cn/help.html)', 'Yahoo! Slurp China')",
        "INSERT INTO `UserAgentTitles` VALUES ('Mozilla/5.0 (compatible; YodaoBot/1.0; http://www.yodao.com/help/webmaster/spider/; )', 'YodaoBot')"
    );

    echo '===> Creating tables, inserting data... ';
    foreach($sql_setup as $sql) {
        $rval = mysql_query($sql . ';');
        if (FALSE === $rval) {
            echo sprintf("FAILED\n     %s [error number %d]\n", mysql_error(),
                mysql_errno());
            return FALSE;
        }
    }

    echo "OK\n";
    return TRUE;
}

function setup_mdb2()
{
    global $DPUNIVERSE_MDB2_DSN, $DPUNIVERSE_MDB2_CONNECT_OPTIONS;

    require_once(DPUNIVERSE_MDB2_PEAR_PATH . 'MDB2.php');

    $dsn = $DPUNIVERSE_MDB2_DSN;
    unset($dsn['database']);

    echo '===> Connecting to database server... ';

    $mdb2 =& MDB2::singleton($dsn, $DPUNIVERSE_MDB2_CONNECT_OPTIONS);
    if (PEAR::isError($mdb2)) {
        $err = $mdb2->getMessage();
        echo $err . "\n";
    } else {
        // loading the Manager module
        $res = $mdb2->loadModule('Manager');
        if (PEAR::isError($res)) {
            "FAILED

         MDB2 reported the following error:
         \"" . $res->getMessage() . "\"\n\n";
            return FALSE;
        }

        $res =& $mdb2->listDatabases();
        if (PEAR::isError($res)) {
            $err = $res->getMessage();
        }
    }

    if (!empty($err)) {
        echo "FAILED

     Failed to connect to the database server. This is probably because of an
     invalid \$DPUNIVERSE_MDB2_DSN in dpuniverse-ini.php or insufficient
     permissions of your database user. Please check if \$DPUNIVERSE_MDB2_DSN is
     correct.\n\n";
        return FALSE;
    }

    echo "OK\n===> Selecting database \"" . $DPUNIVERSE_MDB2_DSN['database']
        . '"... ';

    $mdb2->setDatabase($DPUNIVERSE_MDB2_DSN['database']);
    $res =& $mdb2->query('select 1');

    if (PEAR::isError($res)) {
        echo "FAILED\n     No permission or database \""
            . $DPUNIVERSE_MDB2_DSN['database']
            . "\" does not exist.\n===> Attempting to create database \"{$DPUNIVERSE_MDB2_DSN['database']}\"... ";
        $mdb2 =& MDB2::singleton($dsn, $DPUNIVERSE_MDB2_CONNECT_OPTIONS);
        $mdb2->loadModule('Manager');
        $mdb2->createDatabase($DPUNIVERSE_MDB2_DSN['database']);
        $mdb2->setDatabase($DPUNIVERSE_MDB2_DSN['database']);
        $res =& $mdb2->query('select 1');
        if (PEAR::isError($res)) {
            echo "FAILED

     Failed to select or create database \"{$DPUNIVERSE_MDB2_DSN['database']}\". This is probably
     because of an invalid \$DPUNIVERSE_MDB2_DSN in dpuniverse-ini.php or
     insufficient permissions of your database user (and good security!). Please
     check if \$DPUNIVERSE_MDB2_DSN is correct. If needed, create a database
     called \"{$DPUNIVERSE_MDB2_DSN['database']}\" manually and/or make sure your database user can
     select it, then run this script again.\n\n";
            return FALSE;
        }
        echo "OK\n===> Selecting database \"" . $DPUNIVERSE_MDB2_DSN['database']
            . "\"... OK\n";

    } else {
        echo "OK\n";
    }

    /*
     * CREATE TABLE Users
     */

    echo '===> Creating table "Users"... ';

    @$mdb2->dropSequence('Users');
    $mdb2->dropTable('Users');

    $definition = array(
        'userId' => array(
            'type' => 'integer',
            'length' => 11,
            'unsigned' => 1,
            'notnull' => TRUE
        ),
        'userUsername' => array(
            'type' => 'text',
            'length' => 32,
            'notnull' => TRUE
        ),
        'userPassword' => array(
            'type' => 'text',
            'length' => 32,
            'default' => null
        ),
        'userCookieId' => array(
            'type' => 'text',
            'length' => 32,
            'notnull' => TRUE,
            'default' => ''
        ),
        'userCookiePassword' => array(
            'type' => 'text',
            'length' => 32,
            'notnull' => TRUE,
            'default' => ''
        ),
        'userUsernameLower' => array(
            'type' => 'text',
            'length' => 32,
            'notnull' => TRUE
        ),
        'userAvatarNr' => array(
            'type' => 'integer',
            'length' => 3,
            'unsigned' => 1,
            'notnull' => TRUE,
            'default' => 1
        ),
        'userAvatarCustom' => array(
            'type' => 'text',
            'length' => 9,
            'default' => null
        ),
        'userAge' => array(
            'type' => 'integer',
            'length' => 11,
            'unsigned' => 1,
            'notnull' => TRUE,
            'default' => 0
        ),
        'userDisplayMode' => array(
            'type' => 'text',
            'length' => 32,
            'notnull' => TRUE,
            'default' => 'graphical'
        ),
        'userEventPeopleLeaving' => array(
            'type' => 'text',
            'length' => 1,
            'notnull' => TRUE,
            'default' => '0'
        ),
        'userEventPeopleEntering' => array(
            'type' => 'text',
            'length' => 1,
            'notnull' => TRUE,
            'default' => '0'
        ),
        'userEventBotsEntering' => array(
            'type' => 'text',
            'length' => 1,
            'notnull' => TRUE,
            'default' => '0'
        ),
        'userHomeLocation' => array(
            'type' => 'text',
            'length' => 128,
            'default' => null
        ),
        'userHomeSublocation' => array(
            'type' => 'text',
            'length' => 128,
            'default' => null
        ),
        'userInputMode' => array(
            'type' => 'text',
            'length' => 3,
            'notnull' => TRUE,
            'default' => 'say'
        ),
        'userInputEnabled' => array(
            'type' => 'text',
            'length' => 3,
            'notnull' => TRUE,
            'default' => 'off'
        ),
        'userInputPersistent' => array(
            'type' => 'text',
            'length' => 6,
            'notnull' => TRUE,
            'default' => 'page'
        )
    );
    $mdb2->createTable('Users', $definition);

    $definition = array (
        'primary' => true,
        'fields' => array (
            'userId' => array()
        )
    );
    $mdb2->createConstraint('Users', 'userId', $definition);

    $definition = array (
        'fields' => array (
            'userCookieId' => array(),
            'userCookiePassword' => array()
        )
    );
    $mdb2->createIndex('Users', 'userCookie', $definition);

    @$mdb2->createSequence('Users');

    echo "OK\n";

    /*
     * CREATE TABLE Captcha
     */

    echo '===> Creating table "Captcha"... ';

    @$mdb2->dropSequence('Captcha');
    $mdb2->dropTable('Captcha');

    $definition = array(
        'captchaId' => array(
            'type' => 'integer',
            'length' => 11,
            'unsigned' => 1,
            'notnull' => TRUE
        ),
        'captchaFile' => array(
            'type' => 'text',
            'length' => 32,
            'notnull' => TRUE
        ),
        'captchaTimestamp' => array(
            'type' => 'timestamp',
            'notnull' => TRUE
        )
    );
    $mdb2->createTable('Captcha', $definition);

    $definition = array (
        'primary' => true,
        'fields' => array (
            'captchaId' => array()
        )
    );
    $mdb2->createConstraint('Captcha', 'captchaId', $definition);

    @$mdb2->createSequence('Captcha');

    echo "OK\n";

    echo '===> Inserting data in "Captcha"... ';

    $id = @$mdb2->nextID('Captcha');
    if (PEAR::isError($id)) {
        echo $id->getMessage();
        return FALSE;
    }

    $affected =& $mdb2->exec('INSERT INTO Captcha '
        . '(captchaId, captchaFile, captchaTimestamp) VALUES ('
        . $mdb2->quote($id, 'integer') . ',' . $mdb2->quote('avyrjy.gif',
        'text') . ',' . $mdb2->quote(date('Y-m-d H:i:s'), 'timestamp') . ')');

    // Always check that result is not an error
    if (PEAR::isError($affected)) {
        echo $affected->getMessage();
        return FALSE;
    }

    echo "OK\n";

    /*
     * CREATE TABLE UserAgents
     */

    echo '===> Creating table "UserAgents"... ';

    @$mdb2->dropSequence('UserAgents');
    $mdb2->dropTable('UserAgents');

    $definition = array(
        'userAgentId' => array(
            'type' => 'integer',
            'length' => 11,
            'unsigned' => 1,
            'notnull' => TRUE
        ),
        'userAgentString' => array(
            'type' => 'text',
            'length' => 255,
            'notnull' => TRUE
        ),
        'userAgentRemoteAddress' => array(
            'type' => 'text',
            'length' => 15,
            'default' => null
        )
    );
    $mdb2->createTable('UserAgents', $definition);

    $definition = array (
        'primary' => true,
        'fields' => array (
            'userAgentId' => array()
        )
    );
    $mdb2->createConstraint('UserAgents', 'userAgentId', $definition);

    $definition = array (
        'fields' => array (
            'userAgentString' => array()
        )
    );
    $mdb2->createIndex('UserAgents', 'userAgentString', $definition);

    @$mdb2->createSequence('UserAgents');

    echo "OK\n";

    /*
     * CREATE TABLE UserAgents
     */

    echo '===> Creating table "UserAgentTitles"... ';

    @$mdb2->dropTable('UserAgentTitles');

    $definition = array(
        'userAgentString' => array(
            'type' => 'text',
            'length' => 255,
            'notnull' => TRUE
        ),
        'userAgentTitle' => array(
            'type' => 'text',
            'length' => 64,
            'default' => null
        )
    );
    $mdb2->createTable('UserAgentTitles', $definition);

    $definition = array (
        'primary' => true,
        'fields' => array (
            'userAgentString' => array()
        )
    );
    $mdb2->createConstraint('UserAgentTitles', 'userAgentString', $definition);

    echo "OK\n";

    echo '===> Inserting data in "UserAgentTitles"... ';

    $inserts = array(
        array('Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)', 'Ask Crawler'),
        array('Mozilla/5.0 (compatible; Ask Jeeves/Teoma; +http://about.ask.com/en/docs/about/webmasters.shtml)', 'Ask Crawler'),
        array('Mozilla/2.0 (compatible; Ask Jeeves/Teoma; +http://sp.ask.com/docs/about/tech_crawling.html)', 'Ask Crawler'),
        array('Baiduspider+(+http://www.baidu.com/search/spider_jp.html)', 'Baiduspider'),
        array('Baiduspider+(+http://www.baidu.com/search/spider.htm)', 'Baiduspider'),
        array('Mozilla/5.0 (compatible; BecomeBot/3.0; +http://www.become.com/site_owners.html)', 'BecomeBot'),
        array('Speedy Spider (Entireweb; Beta/1.2; http://www.entireweb.com/about/search_tech/speedyspider/)', 'Entireweb Speedy Spider'),
        array('Speedy Spider (http://www.entireweb.com/about/search_tech/speedyspider/)', 'Entireweb Speedy Spider'),
        array('Gigabot/2.0/gigablast.com/spider.html', 'Gigabot'),
        array('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'Googlebot'),
        array('ichiro/2.0 (http://help.goo.ne.jp/door/crawler.html)', 'Ichiro Web Crawler'),
        array('ilial/Nutch-0.9 (Ilial, Inc. is a Los Angeles based Internet startup company.; http://www.ilial.com/crawler; crawl@ilial.com)', 'Ilial crawler'),
        array('Mozilla/5.0 (compatible; heritrix/1.10.2 +http://i.stanford.edu/)', 'Internet Archive Heritrix Crawler'),
        array('IRLbot/3.0 (compatible; MSIE 6.0; http://irl.cs.tamu.edu/crawler)', 'IRL crawler'),
        array('Krugle/Krugle,Nutch/0.8+ (Krugle web crawler; http://corp.krugle.com/crawler/info.html; webcrawler@krugle.com)', 'Krugle Crawler'),
        array('msnbot/1.0 (+http://search.msn.com/msnbot.htm)', 'MSNBot'),
        array('msnbot-media/1.0 (+http://search.msn.com/msnbot.htm)', 'MSNBot'),
        array('MSRBOT (http://research.microsoft.com/research/sv/msrbot)', 'MSRBot'),
        array('Mozilla/4.0 (compatible; NaverBot/1.0; http://help.naver.com/delete_main.asp)', 'NaverBot'),
        array('Mozilla/4.0 (compatible; Netcraft Web Server Survey)', 'Netcraft Survey Bot'),
        array('psbot/0.1 (+http://www.picsearch.com/bot.html)', 'Psbot (picsearch.com)'),
        array('Sogou web spider/3.0(+http://www.sogou.com/docs/help/webmasters.htm#07)', 'Sogou Spider'),
        array('SurveyBot/2.3 (Whois Source)', 'SurveyBot'),
        array('Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)', 'Yahoo! Slurp'),
        array('Mozilla/5.0 (compatible; Yahoo! Slurp China; http://misc.yahoo.com.cn/help.html)', 'Yahoo! Slurp China'),
        array('Mozilla/5.0 (compatible; YodaoBot/1.0; http://www.yodao.com/help/webmaster/spider/; )', 'YodaoBot'),
    );

    foreach ($inserts as &$insert) {
        $affected =& $mdb2->exec('INSERT INTO UserAgentTitles '
            . '(userAgentString, userAgentTitle) VALUES ('
            . $mdb2->quote($insert[0], 'text') .
            ',' . $mdb2->quote($insert[1], 'text') . ')');

        // Always check that result is not an error
        if (PEAR::isError($affected)) {
            echo $affected->getMessage();
            return FALSE;
        }
    }

    echo "OK\n";
    return TRUE;
}
?>
