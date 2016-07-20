<?php
/**
 * Provides named constants with global universe settings
 *
 * Change these constants to match your desired configuration. These constants
 * define the settings and behavious of "the universe".
 * See dpserver-ini.php for settings dealing with the server related settings.
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage config
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpuniverse-ini.php 47 2006-06-20 22:37:48Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php, dpserver-ini.php
 */

/*
 * MySQL settings
 */

/**
 * MySQL host
 *
 * An empty string if MySQL is running on your local host is sufficient.
 *
 * @see        DPUNIVERSE_MYSQL_USER, DPUNIVERSE_MYSQL_PASSWORD,
 *             DPUNIVERSE_MYSQL_DB
 */
define('DPUNIVERSE_MYSQL_HOST', '');

/**
 * MySQL user name
 *
 * @see        DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_PASSWORD,
 *             DPUNIVERSE_MYSQL_DB
 */
define('DPUNIVERSE_MYSQL_USER', '<youruser>');

/**
 * MySQL password
 *
 * @see        DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER, DPUNIVERSE_MYSQL_DB
 */
define('DPUNIVERSE_MYSQL_PASSWORD', '<yourpass>');

/**
 * MySQL database name, "dutchpipe" by default
 *
 * @see        DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER,
 *             PUNIVERSE_MYSQL_PASSWORD
 */
define('DPUNIVERSE_MYSQL_DB', 'dutchpipe');

/**
 * File owner
 *
 * What user owns the *NIX files and is running the server? Don't run DutchPIPE
 * under "root". Windows users can ignore this.
 */
define('DPUNIVERSE_FILE_OWNER', 'dutchpipe');

/*
 * If you just installed DutchPIPE, haven't changed the directory structure
 * and just want it up and running, you're done here. Make sure you adjusted
 * dpserver-ini.php.
 */

/*
 * Pathnames
 *
 * Leave these untouched if the DutchPIPE directory structure wasn't changed.
 */

/**
 * Path to the root of the DutchPIPE installation
 *
 * Defaults to the mother directory of the directory this file is in.
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_ROOT_PATH', dirname(realpath(__FILE__ . '/..')) . '/');

/**
 * Path to the library directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_LIB_PATH', DPUNIVERSE_ROOT_PATH . 'lib/');

/**
 * Path to the library directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_SCRIPT_PATH', DPUNIVERSE_ROOT_PATH . 'script/');

/**
 * Path to the library directory
 *
 * Excludes trailing /, unlike {@link DPUNIVERSE_PATH} and other paths. Leave
 * this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_PATH
 */
define('DPUNIVERSE_BASE_PATH', DPUNIVERSE_ROOT_PATH . 'dpuniverse');

/**
 * Path to the library directory
 *
 * Includes trailing /, unlike {@link DPUNIVERSE_BASE_PATH}. Leave this
 * untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_BASE_PATH
 */
define('DPUNIVERSE_PATH', DPUNIVERSE_BASE_PATH . '/');

define('DPUNIVERSE_PREFIX_PATH', DPUNIVERSE_BASE_PATH);
/**
 * Path to the universe's page directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_PAGE_PATH', '/page/');

/**
 * Path to the universe's standard building block directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_STD_PATH', '/std/');

/**
 * Path to the universe's object directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_OBJ_PATH', '/obj/');

/**
 * Path to the universe's NPC directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_NPC_PATH', '/npc/');

/**
 * Path to the universe's include directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */

define('DPUNIVERSE_INCLUDE_PATH', '/include/');

/**
 * Path to the publicly reachable web directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_WWW_URL
 */
define('DPUNIVERSE_WWW_PATH', DPUNIVERSE_ROOT_PATH . 'public/');

/**
 * URL to the publicly reachable web directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_WWW_PATH
 */
define('DPUNIVERSE_WWW_URL', '/');

/**
 * Path to the publicly reachable web images directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_IMAGE_URL
 */
define('DPUNIVERSE_IMAGE_PATH', DPUNIVERSE_WWW_PATH . 'images/');

/**
 * URL to the publicly reachable web images directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_IMAGE_PATH
 */
define('DPUNIVERSE_IMAGE_URL', '/images/');

/**
 * Path to the publicly reachable web avatar images directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_URL
 */
define('DPUNIVERSE_AVATAR_PATH', DPUNIVERSE_IMAGE_PATH . 'avatar');

/**
 * URL to the publicly reachable web avatar images directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_PATH
 */
define('DPUNIVERSE_AVATAR_URL', '/images/avatar/');

/**
 * Path to the directory with CAPTCHA images
 *
 * :WARNING: This directory should NOT be web enabled for the public
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_CAPTCHA_IMAGES_PATH', DPUNIVERSE_SCRIPT_PATH
    . 'captcha_images/');

/**
 * Path to the directory with HTML templates
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_TEMPLATE_PATH', DPUNIVERSE_ROOT_PATH . 'template/');

/*
 * System options
 */

/**
 * Experimental, currently not functional, leave it on FALSE
 *
 * @ignore
 */
define('DPUNIVERSE_RUNKIT', FALSE);

/**
 * Error reporting level
 *
 * Which PHP errors to report, according to
 * {@link http://www.php.net/manual/en/ref.errorfunc.php#errorfunc.constants}
 *
 * By default all messages including "strict" messages are shown.
 */
define('DPUNIVERSE_ERROR_REPORTING', E_ALL | E_STRICT);

/*
 * Site behaviour
 */

/**
 * How many secs each cycle for resetDpObject to be called in objects
 */
define('DPUNIVERSE_RESET_CYCLE', 3600);

/**
 * Minimum registered user name length
 *
 * @see        DPUNIVERSE_MAX_USERNAME_LEN
 */
define('DPUNIVERSE_MIN_USERNAME_LEN', 3);

/**
 * Maximum registered user name length
 *
 * @see        DPUNIVERSE_MIN_USERNAME_LEN
 */
define('DPUNIVERSE_MAX_USERNAME_LEN', 12);

/**
 * Default message for invalid commands
 */
define('DPUNIVERSE_ACTION_DEFAULT_FAILURE',
    dptext('What? Enter <tt>help</tt> for a list of commands.<br />'));

/**
 * How many secs must be a connection be dead before a user is thrown out?
 *
 * @see        DPUNIVERSE_LINKDEATH_SHOWMSGTIME, DPUNIVERSE_BOT_KICKTIME,
 *             DPUNIVERSE_LINKDEATH_SHOWBOTTIME
 */
define('DPUNIVERSE_LINKDEATH_KICKTIME', 15);

/**
 * How many secs after someone is thrown do we show a message?
 *
 * The linkdeath user object may be destroyed when single other user enters that
 * page.
 *
 * @see        DPUNIVERSE_LINKDEATH_KICKTIME, DPUNIVERSE_BOT_KICKTIME,
 *             DPUNIVERSE_LINKDEATH_SHOWBOTTIME
 */
define('DPUNIVERSE_LINKDEATH_SHOWMSGTIME', 5);

/**
 * How many secs after a bot (e.g. Google searchbot) is thrown out
 *
 * @see        DPUNIVERSE_LINKDEATH_KICKTIME, DPUNIVERSE_LINKDEATH_SHOWMSGTIME,
 *             DPUNIVERSE_LINKDEATH_SHOWBOTTIME
 */
define('DPUNIVERSE_BOT_KICKTIME', 40);

/**
 * How many secs after a bit is thrown do we show a message
 *
 * Bot may be destroyed when single other user enters that page.
 *
 * @see        DPUNIVERSE_LINKDEATH_KICKTIME, DPUNIVERSE_LINKDEATH_SHOWMSGTIME,
 *             DPUNIVERSE_BOT_KICKTIME
 */
define('DPUNIVERSE_LINKDEATH_SHOWBOTTIME', 5);

/**
 * Maximum number of objects to reset per "cycle"
 */
define('DPUNIVERSE_MAX_RESETS', 10);

/**
 * Used by DutchPIPE.org in the title bar
 */
define('DPUNIVERSE_NAVLOGO', dptext('<img src="/images/navlogo.gif"
align="absbottom" width="73" height="15" border="0" alt="DutchPIPE" />Home'));
?>
