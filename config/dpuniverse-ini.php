<?php
/**
 * Provides named constants with global universe settings
 *
 * Change these constants to match your desired configuration. These constants
 * define the settings and behavious of "the universe".
 * See dpserver-ini.php for settings dealing with the server related settings.
 *
 * DutchPIPE version 0.4; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage config
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpuniverse-ini.php 308 2007-09-02 19:18:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php, dpserver-ini.php
 */

/*
 * Database settings
 *
 * DutchPIPE comes with MySQL sypport out of the box. If this works for you,
 * leave DPUNIVERSE_MDB2_ENABLED set to FALSE and use the DPUNIVERSE_MYSQL_*
 * constants.
 *
 * To use PHP PEAR's MDB2 database abstraction layer, make sure you have MDB2
 * and the module for your database installed, for example by entering in a *NIX
 * shell:
 *
 * $ pear install MDB2
 * $ pear install MDB2#mysql
 *
 * This installs MDB2 and the MySQL module for it. Set DPUNIVERSE_MDB2_ENABLED
 * to TRUE and complete the other DPUNIVERSE_MDB2_* constants.
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
 * Enable MDB2 database abstraction layer instead of using MySQL without it?
 *
 * @see        DPUNIVERSE_MDB2_PEAR_PATH, $DPUNIVERSE_MDB2_DSN,
 *             $DPUNIVERSE_MDB2_CONNECT_OPTIONS
 */
define('DPUNIVERSE_MDB2_ENABLED', FALSE);

/**
 * Path to PEAR installation
 *
 * Leave empty if it is already in the include path, which is most likely the
 * case.
 *
 * @see        DPUNIVERSE_MDB2_ENABLED, $DPUNIVERSE_MDB2_DSN,
 *             $DPUNIVERSE_MDB2_CONNECT_OPTIONS
 */
define('DPUNIVERSE_MDB2_PEAR_PATH', '');

/**
 * Database Data Source Name (DSN) in array format
 *
 * Enter your database settings here. The array has the following format.
 *
 * $GLOBALS['DPUNIVERSE_MDB2_DSN'] = array(
 *     'phptype'  => false,
 *     'dbsyntax' => false,
 *     'username' => false,
 *     'password' => false,
 *     'protocol' => false,
 *     'hostspec' => false,
 *     'port'     => false,
 *     'socket'   => false,
 *     'database' => false,
 *     'new_link' => false,
 *     'service'  => false, // only in oci8
 * );
 *
 * Usually a lot of options can be skipped as your database provides defaults
 * for them. See the links below to the PEAR manual for more information.
 * Examples for a number of databases can be found below.
 *
 * @see        http://pear.php.net/manual/en/package.database.mdb2.intro-dsn.php,
 *             http://pear.php.net/manual/en/package.database.mdb2.intro-connect.php,
 *             $DPUNIVERSE_MDB2_CONNECT_OPTIONS, DPUNIVERSE_MDB2_ENABLED,
 *             DPUNIVERSE_MDB2_PEAR_PATH
 */
$GLOBALS['DPUNIVERSE_MDB2_DSN'] = array(
    'phptype' => 'mysql',
    'database' => 'dutchpipe',
    'username' => '<youruser>',
    'password' => '<yourpass>'
);

/*
$GLOBALS['DPUNIVERSE_MDB2_DSN'] = array(
    'phptype' => 'mysqli',
    'database' => 'dutchpipe',
    'username' => '<youruser>',
    'password' => '<yourpass>'
);

$GLOBALS['DPUNIVERSE_MDB2_DSN'] = array(
    'phptype' => 'sqlite',
    'database' => '/path/to/dutchpipe.db',
    'options' => 'mode=0666'
);

$GLOBALS['DPUNIVERSE_MDB2_DSN'] = array(
    'phptype'  => 'pgsql',
    'username' => '<youruser>',
    'password' => '<yourpass>',
    'database' => 'dutchpipe'
);
*/

/**
 * Optional runtime configuration settings for this database
 *
 * See the link below to the PEAR manual for more information.
 *
 * @see        http://pear.php.net/manual/en/package.database.mdb2.intro-connect.php,
 *             $DPUNIVERSE_MDB2_DSN, DPUNIVERSE_MDB2_ENABLED,
 *             DPUNIVERSE_MDB2_PEAR_PATH
 */
$GLOBALS['DPUNIVERSE_MDB2_CONNECT_OPTIONS'] = array();


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
define('DPUNIVERSE_ROOT_PATH', realpath(dirname(__FILE__) . '/..') . '/');

/**
 * Path to the library directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_LIB_PATH', DPUNIVERSE_ROOT_PATH . 'lib/');

/**
 * Path to the script directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPUNIVERSE_SCRIPT_PATH', DPUNIVERSE_ROOT_PATH . 'script/');

/**
 * Path to the universe directory
 *
 * Excludes trailing /, unlike {@link DPUNIVERSE_PATH} and other paths. Leave
 * this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_PATH
 */
define('DPUNIVERSE_BASE_PATH', DPUNIVERSE_ROOT_PATH . 'dpuniverse');

/**
 * Path to the universe directory
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
 * The absolute path after the host name of the URL.
 * If DutchPIPE is installed on http://www.example.com/, this path is /.
 * On http://www.example.com/subdir/ it would be /subdir/.
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
define('DPUNIVERSE_IMAGE_URL', DPUNIVERSE_WWW_URL . 'images/');

/**
 * Path to the publicly reachable standard avatar images directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_STD_URL
 */
define('DPUNIVERSE_AVATAR_STD_PATH', DPUNIVERSE_IMAGE_PATH . 'ava_std/');

/**
 * URL to the publicly reachable standard avatar images directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_STD_PATH
 */
define('DPUNIVERSE_AVATAR_STD_URL', DPUNIVERSE_IMAGE_URL . 'ava_std/');

/**
 * Are users allowed to upload their own avatar?
 *
 * TRUE to enable, FALSE to disable. The GD library must be enabled on your PHP
 * installation. This constant is ignored when GD is not enabled.
 */
define('DPUNIVERSE_AVATAR_CUSTOM_ENABLED', TRUE);

/**
 * Path to the publicly reachable custom avatar directory of guests
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_CUSTOM_GUEST_URL
 */
define('DPUNIVERSE_AVATAR_CUSTOM_GUEST_PATH', DPUNIVERSE_IMAGE_PATH
    . 'ava_cus_gst/');

/**
 * URL to the publicly reachable custom avatar directory of guests
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_CUSTOM_GUEST_PATH
 */
define('DPUNIVERSE_AVATAR_CUSTOM_GUEST_URL', DPUNIVERSE_IMAGE_URL
    . 'ava_cus_gst/');

/**
 * Path to the publicly reachable custom avatar directory of registered users
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_CUSTOM_REG_URL
 */
define('DPUNIVERSE_AVATAR_CUSTOM_REG_PATH', DPUNIVERSE_IMAGE_PATH
    . 'ava_cus_reg/');

/**
 * URL to the publicly reachable custom avatar directory of registered users
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPUNIVERSE_AVATAR_CUSTOM_REG_PATH
 */
define('DPUNIVERSE_AVATAR_CUSTOM_REG_URL', DPUNIVERSE_IMAGE_URL
    . 'ava_cus_reg/');

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
 * Is it mandatory for the browser to report a user agent string?
 *
 * Every browser reports a user agent string to the web server, such as:
 * "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322;
 * .NET CLR 2.0.50727)" or
 * "Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.8.0.4) Gecko/20060508
 * Firefox/1.5.0.4".
 *
 * Browsers which don't report such a string get an error message if this
 * constant is set to TRUE.
 *
 * Usually, the only "browsers" with do not report a user agent string are
 * harvest bots by spammers. However, it seems sometimes this leads to undesired
 * effects, in which case you can set this constant to FALSE.
 */
define('DPUNIVERSE_USERAGENT_MANDATORY', TRUE);

/**
 * Default message for invalid commands
 */
define('DPUNIVERSE_ACTION_DEFAULT_FAILURE',
    dp_text('What? Enter <tt>help</tt> for a list of commands.<br />'));

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
define('DPUNIVERSE_NAVLOGO', sprintf(dp_text('<img src="%snavlogo.gif"
align="left" width="73" height="15" border="0" alt="DutchPIPE"
style="margin-top: 1px" />Home'), DPUNIVERSE_IMAGE_URL));

/**
 * Administrators registered user names separated by the # character
 */
define('DPUNIVERSE_ADMINISTRATORS', 'Lennert');
?>
