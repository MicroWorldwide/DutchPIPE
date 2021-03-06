<?php
/**
 * Constants with global server settings
 *
 * Change these constants to match your desired configuration. These constants
 * define the settings and behavious of {@link dpserver.php} and
 * {@link dpclient.php}, that is, the communication between the DutchPIPE server
 * and the user's browser. See {@link dpuniverse-ini.php} for settings dealing
 * with the "universe".
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
 * @version    Subversion: $Id: dpserver-ini.php 308 2007-09-02 19:18:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver.php, dpclient.php, dpuniverse-ini.php
 */

/**
 * Host on which DutchPIPE is running
 *
 * The URL part minus the absolute path to {@link dpclient.php} (/dpclient.php
 * by default, as defined in {@link DPSERVER_CLIENT_URL}).
 *
 * @see        DPSERVER_CLIENT_URL, DPSERVER_CLIENTJS_URL
 */
define('DPSERVER_HOST_URL', 'http://www.yourdomain.com');
//define('DPSERVER_HOST_URL', 'http://localhost');

if (!defined('DPSERVER_CLIENT_DIR')) {
    /**
     * The rest of the URL for the directory to to the client PHP script, / by
     * default
     *
     * @see        DPSERVER_HOST_URL, DPSERVER_CLIENTJS_URL
     */
    define('DPSERVER_CLIENT_DIR', '/');
}

if (!defined('DPSERVER_CLIENT_FILENAME')) {
    /**
     * The rest of the URL for the client PHP script, dpclient.php by default
     *
     * @see        DPSERVER_HOST_URL, DPSERVER_CLIENTJS_URL
     */
    define('DPSERVER_CLIENT_FILENAME', 'dpclient.php');
}

if (!defined('DPSERVER_CLIENT_URL')) {
    /**
     * The rest of the URL for the client PHP script, /dpclient.php by default
     *
     * @see        DPSERVER_HOST_URL, DPSERVER_CLIENTJS_URL
     */
    define('DPSERVER_CLIENT_URL', DPSERVER_CLIENT_DIR .
        DPSERVER_CLIENT_FILENAME);
}

/**
 * The rest of the URL for the client Javascript, /dpclient-js.php by default
 *
 * @see        DPSERVER_HOST_URL, DPSERVER_CLIENTJS_URL
 */
define('DPSERVER_CLIENTJS_URL', DPSERVER_CLIENT_DIR . 'dpclient-js.php');

/**
 * The name of the cookie DutchPIPE uses to store user information
 */
define('DPSERVER_COOKIE_NAME', 'dutchpipe');

/**
 * The timezone for the DutchPIPE site
 *
 * Use an identifier as definied by
 * {@link http://www.php.net/manual/en/timezones.php}.
 */
define('DPSERVER_TIMEZONE', 'Europe/Amsterdam');

/**
 * Type of socket {@link dpserver.php} and {@link dpclient.php} use to
 * communicate
 *
 * See {@link http://www.php.net/socket_create} for more information.
 * For *NIX, use AF_UNIX, for Windows, use AF_INET. AF_UNIX is a file based
 * socket, AF_INET uses a local connection (can be remote but that is untested).
 * AF_UNIX is much faster but isn't supported on Windows.
 *
 * @see        DPSERVER_SOCKET_PATH, DPSERVER_SOCKET_ADDRESS,
 *             DPSERVER_SOCKET_PORT, DPSERVER_MAX_SOCKET_BACKLOG,
 *             DPSERVER_SOCKERR_MSG
 */
define('DPSERVER_SOCKET_TYPE', AF_INET);
//define('DPSERVER_SOCKET_TYPE', AF_UNIX);

/**
 * Path to the file used for socket connections
 *
 * This file socket is used beween {@link dpserver.php} and {@link dpclient.php}
 * for AF_UNIX type. Reads and writes to this open file to communicate.
 *
 * @see        DPSERVER_SOCKET_TYPE, DPSERVER_SOCKET_ADDRESS,
 *             DPSERVER_SOCKET_PORT, DPSERVER_MAX_SOCKET_BACKLOG,
 *             DPSERVER_SOCKERR_MSG
 */
define('DPSERVER_SOCKET_PATH', '/tmp/dutchpipe.sock');

/**
 * IP address for AF_INET type
 *
 * If you run DutchPIPE on Windows, this value will always be used in
 * combination with {@link DPSERVER_SOCKET_PORT}. The DutchPIPE server will run
 * on your machine using the address and port number provided.
 *
 * @see        DPSERVER_SOCKET_TYPE, DPSERVER_SOCKET_PATH,
 *             DPSERVER_SOCKET_PORT, DPSERVER_MAX_SOCKET_BACKLOG,
 *             DPSERVER_SOCKERR_MSG
 */
define('DPSERVER_SOCKET_ADDRESS', '127.0.0.1');

/**
 * Port number for AF_INET type
 *
 * If you run DutchPIPE on Windows, this value will always be used in
 * combination with {@link DPSERVER_SOCKET_ADDRESS}. The DutchPIPE server will
 * run on your machine using the address and port number provided. You should
 * check if the port number is not in use by another application.
 *
 * @see        DPSERVER_SOCKET_TYPE, DPSERVER_SOCKET_PATH,
 *             DPSERVER_SOCKET_ADDRESS, DPSERVER_MAX_SOCKET_BACKLOG,
 *             DPSERVER_SOCKERR_MSG
 */
define('DPSERVER_SOCKET_PORT', '3333');

/*
 * If you just installed DutchPIPE, haven't changed the directory structure
 * and just want it up and running, you're done here. Now adjust
 * {@link dpuniverse-ini.php}. If you want multilingual support, see further
 * below.
 */

/**
 * The maximum of backlog incoming connections queued for processing
 *
 * Used by socket_listen in dpserver.php. See
 * {@link http://www.php.net/socket_listen} for more information.
 *
 * You should probably leave this untouched.
 *
 * @see        DPSERVER_SOCKET_TYPE, DPSERVER_SOCKET_PATH,
 *             DPSERVER_SOCKET_ADDRESS, DPSERVER_SOCKET_PORT
 */
define('DPSERVER_MAX_SOCKET_BACKLOG', 5);

/**
 * Path to the root of the DutchPIPE installation
 *
 * Defaults to the mother directory of the directory this file is in.
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPSERVER_ROOT_PATH', realpath(dirname(__FILE__) . '/..') . '/');

/**
 * Path to the root of the DutchPIPE universe
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPSERVER_DPUNIVERSE_PATH', DPSERVER_ROOT_PATH . 'dpuniverse/');

/**
 * Path to the directory with server and universe settings
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPSERVER_DPUNIVERSE_CONFIG_PATH', DPSERVER_ROOT_PATH . 'config/');

/**
 * Path to the library directory
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPSERVER_LIB_PATH', DPSERVER_ROOT_PATH . 'lib/');

/**
 * Path to the file with the DutchPIPE Server class
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPSERVER_DPSERVERCLASS_PATH', DPSERVER_LIB_PATH . 'dpserver.php');

/**
 * Path to the file with the DutchPIPE Universe class
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPSERVER_DPUNIVERSECLASS_PATH', DPSERVER_LIB_PATH . 'dpuniverse.php');

/**
 * Path to the directory with HTML templates
 *
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 *
 * @see        DPSERVER_TEMPLATE_FILE, DPSERVER_TEMPLATE_DOWN_FILE
 */
define('DPSERVER_TEMPLATE_PATH', DPSERVER_ROOT_PATH . 'template/');

if (!defined('DPSERVER_TEMPLATE_FILE')) {
    /**
     * Filename of the default template in the {@link DPSERVER_TEMPLATE_PATH}
     * directory
     *
     * @see        DPSERVER_TEMPLATE_PATH, DPSERVER_TEMPLATE_DOWN_FILE
     */
    define('DPSERVER_TEMPLATE_FILE', 'dpdefault.tpl');
}

/**
 * Filename of the template in the {@link DPSERVER_TEMPLATE_PATH}
 * directory used when the DutchPIPE server is down.
 *
 * @see        DPSERVER_TEMPLATE_PATH, DPSERVER_TEMPLATE_FILE
 */
define('DPSERVER_TEMPLATE_DOWN_FILE', 'dpdown.tpl');

/**
 * Enable internationalization/localization support with dynamic gettext?
 *
 * This has a speed penalty. Set to TRUE to enable or FALSE to disable.
 *
 * Different languages are supported using GNU gettext, see
 * {@link http://www.php.net/gettext}. Because PHP is an interpreted language,
 * gettext slows things down as calls are dynamic (although it's quite fast).
 * There are some tools around to make static PHP translations, but this has not
 * been explored yet. Also, it is not yet possible to have multiple users use
 * different languages on one site. It's in one language.
 *
 * Currently, standard English ("en") and Dutch ("nl_NL")  are part of the
 * standard distribution. Also, all texts to translate are in only one file
 * called messages.po (this will change in the future).
 *
 * If you'd like to translate for DutchPIPE, base your translation on
 * locale/en/LC_MESSAGES/messages.po and please mail your results to
 * contributors@dutchpipe.org. As I did with the Dutch translation, you'll
 * probably run into a couple of problems getting it exactly right, because
 * of presumptions made by the English language system. Please let us know
 * so this can be fixed.
 *
 * @see        DPSERVER_GETTEXT_LOCALE_PATH, DPSERVER_GETTEXT_DOMAIN,
 *             DPSERVER_GETTEXT_ENCODING, DPSERVER_LOCALE, DPSERVER_LOCALE_FULL,
 *             DPSERVER_ENABLE_MBSTRING
 */
define('DPSERVER_GETTEXT_ENABLED', FALSE);

/**
 * Path to the directory with translations
 *
 * Used when gettext is enabled.
 *
 * @see        DPSERVER_GETTEXT_ENABLED, DPSERVER_GETTEXT_DOMAIN,
 *             DPSERVER_GETTEXT_ENCODING, DPSERVER_LOCALE, DPSERVER_LOCALE_FULL,
 *             DPSERVER_ENABLE_MBSTRING
 */
define('DPSERVER_GETTEXT_LOCALE_PATH', DPSERVER_ROOT_PATH . 'locale/');

/**
 * Name of translation table (the "domain" in GNU gettext jargon)
 *
 * Used when gettext is enabled.
 *
 * @see        DPSERVER_GETTEXT_ENABLED, DPSERVER_GETTEXT_LOCALE_PATH,
 *             DPSERVER_GETTEXT_ENCODING, DPSERVER_LOCALE, DPSERVER_LOCALE_FULL,
 *             DPSERVER_ENABLE_MBSTRING
 */
define('DPSERVER_GETTEXT_DOMAIN', 'messages');

/**
 * Gettext character encoding
 *
 * Used when gettext is enabled.
 * DutchPIPE uses UTF-8 but I'm not sure how this works for other languages
 *
 * @see        DPSERVER_GETTEXT_ENABLED, DPSERVER_GETTEXT_LOCALE_PATH,
 *             DPSERVER_GETTEXT_DOMAIN, DPSERVER_LOCALE, DPSERVER_LOCALE_FULL,
 *             DPSERVER_ENABLE_MBSTRING
 */
define('DPSERVER_GETTEXT_ENCODING', 'UTF-8');

/**
 * Used when gettext is enabled, defines a "locale" supported by DutchPIPE
 *
 * Different systems have different naming schemes for locales, see
 * {@link setlocale}.
 *
 * Use the '0' string to leave the locale settings untouched and use the
 * default language (English in the standard distribution of DutchPIPE).
 *
 * To use one of the translations included in the standard DutchPIPE
 * distribution, outcomment the first define which sets DPSERVER_LOCALE to '0',
 * and remove comment characters from the language in the list below.
 *
 * @see        DPSERVER_GETTEXT_ENABLED, DPSERVER_GETTEXT_LOCALE_PATH,
 *             DPSERVER_GETTEXT_DOMAIN, DPSERVER_GETTEXT_ENCODING,
 *             DPSERVER_LOCALE_FULL, DPSERVER_ENABLE_MBSTRING
 */
define('DPSERVER_LOCALE', '0');
//define('DPSERVER_LOCALE', 'nl_NL');

/**
 * Multibyte string handling enabled?
 *
 * Enables your site to handle all UTF-8 chars, so people can communicate for
 * example in East Asian languages. PHP must have the multibyte extension
 * enabled if set to TRUE, otherwise this setting is ignored. Set to FALSE to
 * disable.
 *
 * See also: http://www.php.net/manual/en/ref.mbstring.php
 *
 * @see        DPSERVER_GETTEXT_ENABLED, DPSERVER_GETTEXT_LOCALE_PATH,
 *             DPSERVER_GETTEXT_DOMAIN, DPSERVER_GETTEXT_ENCODING,
 *             DPSERVER_LOCALE_FULL, DPSERVER_LOCALE
 * @since      DutchPIPE 0.4.0
 */
define('DPSERVER_ENABLE_MBSTRING', TRUE);

/**
 * You probably don't need to touch this
 *
 * @see        DPSERVER_GETTEXT_ENABLED, DPSERVER_GETTEXT_LOCALE_PATH,
 *             DPSERVER_GETTEXT_DOMAIN, DPSERVER_GETTEXT_ENCODING,
 *             DPSERVER_LOCALE
 */
define('DPSERVER_LOCALE_FULL', DPSERVER_LOCALE == '0'
    ? '0' : DPSERVER_LOCALE . '.' . DPSERVER_GETTEXT_ENCODING);

/**
 * Don't touch this
 *
 * @access     private
 */
require_once(DPSERVER_LIB_PATH . 'dptext.php');

/**
 * The maximum number of seconds the server can stay up, 0 for no limit
 */
define('DPSERVER_MAXUPTIME', 0);

/**
 * The message shown in case of a socket error
 *
 * This usually means the server is down.
 *
 * @see        DPSERVER_SOCKET_TYPE, DPSERVER_SOCKET_PATH,
 *             DPSERVER_SOCKET_ADDRESS, DPSERVER_SOCKET_PORT,
 *             DPSERVER_MAX_SOCKET_BACKLOG
 */
define('DPSERVER_SOCKERR_MSG', dp_text('The DutchPIPE server is down'));

/**
 * Error reporting level
 *
 * Which PHP errors to report, according to
 * {@link http://www.php.net/manual/en/ref.errorfunc.php#errorfunc.constants}
 *
 * By default all messages including "strict" messages are shown.
 *
 * @see        DPSERVER_DEBUG_TYPE
 */
define('DPSERVER_ERROR_REPORTING', E_ALL | E_STRICT);

/**
 * Debug information modifier - don't show debug information
 *
 * Used by {@link DPSERVER_DEBUG_TYPE}. Don't change this.
 *
 * @see        DPSERVER_DEBUG_TYPE, DPSERVER_DEBUG_TYPE_MEMORY_GET_USAGE,
 *             DPSERVER_DEBUG_TYPE_GETRUSAGE
 */
define('DPSERVER_DEBUG_TYPE_NONE', 1);

/**
 * Debug information modifier - show info line with memory and object counters
 *
 * *NIX only, doesn't work under Windows.
 * Used by {@link DPSERVER_DEBUG_TYPE}. Don't change this.
 *
 * @see        DPSERVER_DEBUG_TYPE, DPSERVER_DEBUG_TYPE_NONE,
 *             DPSERVER_DEBUG_TYPE_GETRUSAGE
 */
define('DPSERVER_DEBUG_TYPE_MEMORY_GET_USAGE', 2);

/**
 * Debug information modifier - show info based on *nix getrusage
 *
 * *NIX only, doesn't work under Windows.
 * Used by {@link DPSERVER_DEBUG_TYPE}. Don't change this.
 *
 * @see        DPSERVER_DEBUG_TYPE, DPSERVER_DEBUG_TYPE_NONE,
 *             DPSERVER_DEBUG_TYPE_MEMORY_GET_USAGE
 */
define('DPSERVER_DEBUG_TYPE_GETRUSAGE', 3);

/**
 * Debug information settings
 *
 * What kind of debug information should the server echo to the CLI/shell when
 * running? The DPSERVER_DEBUG_TYPE must be one of:
 *
 * - {@link DPSERVER_DEBUG_TYPE_NONE}<br>
 *   Don't show debug information. You must use this under Windows.
 * - {@link DPSERVER_DEBUG_TYPE_MEMORY_GET_USAGE}<br>
 *   Show info line with memory and object counters. *NIX only, doesn't work
 *   under Windows.
 * - {@link DPSERVER_DEBUG_TYPE_GETRUSAGE}<br>
 *   Show info based on *nix getrusage. *NIX only, doesn't work under Windows.
 *
 * @see        DPSERVER_DEBUG_TYPE_NONE, DPSERVER_DEBUG_TYPE_MEMORY_GET_USAGE,
 *             DPSERVER_DEBUG_TYPE_GETRUSAGE, DPSERVER_ERROR_REPORTING
 */
define('DPSERVER_DEBUG_TYPE', DPSERVER_DEBUG_TYPE_NONE);

/**
 * Use base64_encode/decode on dpclient -> dpserver communication?
 *
 * If you are getting "unserialize" errors, set to TRUE. This has a small speed
 * penalty.
 *
 * @see        DPSERVER_BASE64_SERVER2CLIENT
 */
define('DPSERVER_BASE64_CLIENT2SERVER', TRUE);

/**
 * Use base64_encode/decode on dpserver -> dpclient communication?
 *
 * This has a small speed penalty.
 *
 * @see        DPSERVER_BASE64_CLIENT2SERVER
 */
define('DPSERVER_BASE64_SERVER2CLIENT', TRUE);

/**
 * Maximum number of bytes {@link dpclient.php} can read from
 * {@link dpserver.php} per chunk
 *
 * You should probably leave this untouched.
 *
 * @see        DPSERVER_SERVER_CHUNK
 */
define('DPSERVER_CLIENT_CHUNK', 2048);

/**
 * Maximum number of bytes {@link dpserver.php} can read from
 * {@link dpclient.php} per chunk
 *
 * You should probably leave this untouched.
 *
 * @see        DPSERVER_CLIENT_CHUNK
 */
define('DPSERVER_SERVER_CHUNK', 2048);

/**
 * Maximum memory usage
 *
 * Memory limit in bytes that the server may allocate. Use -1 for unlimited.
 * When an integer is used, the value is measured in bytes. You may also use
 * shorthand notation as described in this FAQ:
 * http://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
 *
 * See also: http://www.php.net/manual/en/ini.core.php#ini.memory-limit
 */
define('DPSERVER_MEMORY_LIMIT', '32M');

/**
 * Apache minimal deflate/gzip size
 *
 * If you are using Apache's mod_deflate (or mod_gzip), even small Ajax requests
 * are compressed, which is not effective. You can set a minimal size in bytes
 * here the content of the request should have (200 is a good size). The
 * DutchPIPE client will use the following PHP function call for small requests:
 *    apache_setenv('no-gzip', '1');
 * Set to FALSE if you're not running Apache with output compression or if you
 * don't want to use this feature.
 */
define('DPSERVER_APACHE_GZIP_MIN', FALSE);
//define('DPSERVER_APACHE_GZIP_MIN', 200);

/**
 * Maximum width of object images in pixels
 *
 * @see        DPSERVER_OBJECT_IMAGE_MAX_HEIGHT
 */
define('DPSERVER_OBJECT_IMAGE_MAX_WIDTH', 95);

/**
 * Maximum height of object images in pixels
 *
 * @see        DPSERVER_OBJECT_IMAGE_MAX_WIDTH
 */
define('DPSERVER_OBJECT_IMAGE_MAX_HEIGHT', 100);

/**
 * Comma separated list of file extensions allowed for custom avatars and images
 *
 * @see        DPSERVER_OBJECT_IMAGE_CUSTOM_MAX_SIZE
 */
define('DPSERVER_OBJECT_IMAGE_VALID_TYPES', 'gif,jpg,png');

/**
 * Maximum size in bytes of custom image uploads for avatars and other images
 *
 * @see        DPSERVERE_OBJECT_IMAGE_VALID_TYPES
 */
define('DPSERVER_OBJECT_IMAGE_CUSTOM_MAX_SIZE', 1048576);

/**
 * Maximum time in seconds the user can browse for a file during AJAX upload
 *
 * Browsing causes scripts to halt in a browser, and we don't want to throw
 * people out. This should be a bigger number than
 * DPUNIVERSE_LINKDEATH_KICKTIME.
 *
 * @see        DPUNIVERSE_LINKDEATH_KICKTIME
 */
define('DPSERVER_OBJECT_IMAGE_CUSTOM_MAX_BROWSETIME', 60);
?>
