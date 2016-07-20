<?php
/**
 * Provides named constants with global universe settings
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpuniverse-ini.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

/*
 * Pathnames
 */
define('DPUNIVERSE_ROOT_BASE_PATH', dirname(realpath(__FILE__ . '/..')));
define('DPUNIVERSE_ROOT_PATH', DPUNIVERSE_ROOT_BASE_PATH . '/');
define('DPUNIVERSE_LIB_BASE_PATH', DPUNIVERSE_ROOT_PATH . 'lib');
define('DPUNIVERSE_LIB_PATH', DPUNIVERSE_LIB_BASE_PATH . '/');
define('DPUNIVERSE_SCRIPT_PATH', DPUNIVERSE_ROOT_PATH . 'script/');
define('DPUNIVERSE_BASE_PATH', DPUNIVERSE_ROOT_PATH . 'dpuniverse');
define('DPUNIVERSE_PATH', DPUNIVERSE_BASE_PATH . '/');
define('DPUNIVERSE_PAGE_PATH', '/page/');
define('DPUNIVERSE_STD_PATH', '/std/');
define('DPUNIVERSE_OBJ_PATH', '/obj/');
define('DPUNIVERSE_NPC_PATH', '/npc/');
define('DPUNIVERSE_INCLUDE_PATH', '/include/');
define('DPUNIVERSE_WWW_PATH', DPUNIVERSE_ROOT_PATH . 'public/');
define('DPUNIVERSE_WWW_URL', '/');
define('DPUNIVERSE_IMAGE_PATH', DPUNIVERSE_WWW_PATH . 'images/');
define('DPUNIVERSE_IMAGE_URL', '/images/');
define('DPUNIVERSE_AVATAR_PATH', DPUNIVERSE_IMAGE_PATH . 'avatar');
define('DPUNIVERSE_AVATAR_URL', '/images/avatar/');
define('DPUNIVERSE_CAPTCHA_IMAGES_PATH', DPUNIVERSE_SCRIPT_PATH
    . 'captcha_images/');
define('DPUNIVERSE_TEMPLATE_PATH', DPUNIVERSE_ROOT_PATH . 'template/');

/*
 * File owners/permissions
 *
 * What user owns the *NIX files?
 */
define('DPUNIVERSE_FILE_OWNER', 'dutchpipe');

/*
 * System options
 */
define('DPUNIVERSE_RUNKIT', FALSE); /* Experimental, currently not functional */
define('DPUNIVERSE_ERROR_REPORTING', E_ALL | E_STRICT);

/*
 * MySQL
 */
define('DPUNIVERSE_MYSQL_HOST', '');
define('DPUNIVERSE_MYSQL_USER', '<youruser>');
define('DPUNIVERSE_MYSQL_PASSWORD', '<yourpass>');
define('DPUNIVERSE_MYSQL_DB', 'dutchpipe');

/*
 * Site behaviour
 */

/* How many secs each cycle for resetDpObject to be called in objects */
define('DPUNIVERSE_RESET_CYCLE', 3600);

/* Minimum registered user name length */
define('DPUNIVERSE_MIN_USERNAME_LEN', 3);

/* Maximum registered user name length */
define('DPUNIVERSE_MAX_USERNAME_LEN', 12);

/* Default message for invalid commands */
define('DPUNIVERSE_ACTION_DEFAULT_FAILURE',
'What? Enter <tt>help</tt> for a list of commands.<br />');

/* How many secs must be a connection be dead before a user is thrown out? */
define('DPUNIVERSE_LINKDEATH_KICKTIME', 15);

/*
 * How many secs after someone is thrown do we show a message (ld person may be
 * destroyed when single other user enters that page)
 */
define('DPUNIVERSE_LINKDEATH_SHOWMSGTIME', 5);

/* How many secs after a bot (e.g. Google searchbot) is thrown out */
define('DPUNIVERSE_BOT_KICKTIME', 40);

/*
 * How many secs after a bit is thrown do we show a message (bot may be
 * destroyed when single other user enters that page)
 */
define('DPUNIVERSE_LINKDEATH_SHOWBOTTIME', 5);

/* Maximum number of objects to reset per "cycle" */
define('DPUNIVERSE_MAX_RESETS', 10);

/* Used by DutchPIPE.org in the title bar */
define('DPUNIVERSE_NAVLOGO', '<img src="/images/navlogo.gif" align="absbottom" '
    . 'width="73" height="15" border="0" alt="DutchPIPE" />Home');

?>
