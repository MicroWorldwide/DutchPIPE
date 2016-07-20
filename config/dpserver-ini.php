<?php
/**
 * Named constants with global server settings
 *
 * Change thse constants to match your desired configuration.
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
 * @version    Subversion: $Id: dpserver-ini.php 22 2006-05-30 20:40:55Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver.php
 */

/**
 * Host on which DutchPIPE is running, the URL part minus dpclient.php
 */
define('DPSERVER_HOST_URL', 'http://www.yourdomain.com');

/**
 * The rest of the URL for the client PHP script, /dpclient.php by default
 */
define('DPSERVER_CLIENT_URL', '/dpclient.php');

/**
 * The rest of the URL for the client PHP script, /dpclient.php by default
 */
define('DPSERVER_CLIENTJS_URL', '/dpclient-js.php');

/**
 * Path to the root of the DutchPIPE installation
 *
 * Defaults to the mother directory of the directory this file is in.
 * Leave this untouched if the DutchPIPE directory structure wasn't changed.
 */
define('DPSERVER_ROOT_PATH', dirname(realpath(__FILE__ . '/..')) . '/');

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
 */
define('DPSERVER_DPUNIVERSE_TEMPLATE_PATH', DPSERVER_ROOT_PATH . 'template/');

/**
 * Type of socket dpserver.php and dpclient.php use to communicate
 * See http://www.php.net/socket_create for more information.
 * For *NIX, use AF_UNIX, for Windows, use AF_INET. AF_UNIX is a file based
 * socket, AF_INET uses a local connection (can be remote but that is untested).
 * AF_UNIX is much faster but isn't supported on Windows.
 */
define('DPSERVER_SOCKET_TYPE', AF_UNIX);
//define('DPSERVER_SOCKET_TYPE', AF_INET);

/**
 * Path to the file used for socket connections beween dpserver.php and
 * dpclient.php for AF_UNIX type
 */
define('DPSERVER_SOCKET_PATH', '/tmp/dutchpipesock');

/**
 * Address and port for AF_INET type
 *
 * If you run DutchPIPE on Windows, these values will always be used.
 * The DutchPIPE server will run on your machine using the address and port
 * number provided. You should check if the port number is not in use by
 * another application.
 */
define('DPSERVER_SOCKET_ADDRESS', '127.0.0.1');
define('DPSERVER_SOCKET_PORT', '3333');

/**
 * The maximum of backlog incoming connections queued for processing
 *
 * Used by socket_listen in dpserver.php. See http://www.php.net/socket_listen
 *
 * You should probably leave this untouched.
 */
define('DPSERVER_MAX_SOCKET_BACKLOG', 5);

/**
 * The name of the cookie DutchPIPE uses to store user information
 */
define('DPSERVER_COOKIE_NAME', 'dutchpipe');

/*
 * Internationalization
 *
 * Different languages are supported using GNU gettext, see
 * http://www.php.net/gettext. Because PHP is an interpreted language, gettext
 * slows things down as calls are dynamic (although it's quite fast). There are
 * some tools around to make static PHP translations, but this has not been
 * explored yet. Also, it is not yet possible to have multiple users use
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
 */

/*
 * Enable dynamic gettext? This will slow things down. Set to TRUE to enable,
 * or FALSE to disable.
 */
define('DPSERVER_GETTEXT_ENABLED', FALSE);

/**
 * Used when gettext is enabled.
 * Path to the directory with translations
 */
define('DPSERVER_GETTEXT_LOCALE_PATH', DPSERVER_ROOT_PATH . 'locale/');

/**
 * Used when gettext is enabled.
 * Name of translation table (the "domain" in GNU gettext jargon)
 */
define('DPSERVER_GETTEXT_DOMAIN', 'messages');

/**
 * Used when gettext is enabled.
 * DutchPIPE uses UTF-8 but I'm not sure how this works for other languages
 */
define('DPSERVER_GETTEXT_ENCODING', 'UTF-8');

/**
 * Used when gettext is enabled, defines a "locale" supported by DutchPIPE
 *
 * Different systems have different naming schemes for locales, see
 * http://www.php.net/setlocale
 *
 * Use the '0' string to leave the locale settings untouched and use the
 * default language (English in the standard distribution of DutchPIPE).
 *
 * To use one of the translations included in the standard DutchPIPE
 * distribution, outcomment the first define which sets DPSERVER_LOCALE to '0',
 * and remove comment characters from the language in the list below.
 */
define('DPSERVER_LOCALE', '0');
//define('DPSERVER_LOCALE', 'nl_NL');

/* You probably don't need to touch this */
define('DPSERVER_LOCALE_FULL', DPSERVER_LOCALE == '0'
    ? '0' : DPSERVER_LOCALE . '.' . DPSERVER_GETTEXT_ENCODING);

/* Don't touch this */
require_once(DPSERVER_LIB_PATH . 'dptext.php');

/**
 * The timezone we're in using an identifier as definied by
 * http://www.php.net/manual/en/timezones.php
 */
define('DPSERVER_TIMEZONE', 'Europe/Amsterdam');

/**
 * The maximum number of seconds the server can stay up, 0 for no limit
 */
define('DPSERVER_MAXUPTIME', 0);

/**
 * The message shown in case of a socket error (this usually means the server is
 * down)
 */
define('DPSERVER_SOCKERR_MSG', dptext('The DutchPIPE server is down'));

/**
 * Which errors to report according to
 * http://www.php.net/manual/en/ref.errorfunc.php#errorfunc.constants
 *
 * By default all messages including "strict" messages are shown.
 */
define('DPSERVER_ERROR_REPORTING', E_ALL | E_STRICT);

/**
 * What kind of debug information should the server echo to the shell when
 * running? Change the DPSERVER_DEBUG_TYPE with one of the constants listed
 * here (don't change these). DPSERVER_DEBUG_TYPE_MEMORY_GET_USAGE and
 * DPSERVER_DEBUG_TYPE_GETRUSAGE makes dpserver show a line with information
 * at intervals. They don't work under Windows.
 */
define('DPSERVER_DEBUG_TYPE_NONE', 1);
define('DPSERVER_DEBUG_TYPE_MEMORY_GET_USAGE', 2);
define('DPSERVER_DEBUG_TYPE_GETRUSAGE', 3);
define('DPSERVER_DEBUG_TYPE', DPSERVER_DEBUG_TYPE_NONE);

/**
 * Maximum number of bytes dpclient.php can read from dpserver.php per chunk
 *
 * You should probably leave this untouched.
 */
define('DPSERVER_CLIENT_CHUNK', 2048);

/**
 * Maximum number of bytes dpserver.php can read from dpclient.php per chunk
 *
 * You should probably leave this untouched.
 */
define('DPSERVER_SERVER_CHUNK', 2048);
?>
