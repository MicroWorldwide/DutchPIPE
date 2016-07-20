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
 * @version    Subversion: $Id: dpserver-ini.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver.php
 */

/**
 * Path to the root of the DutchPIPE installation
 */
define('DPSERVER_ROOT_PATH', dirname(realpath(__FILE__ . '/..')) . '/');

/**
 * Path to the root of the DutchPIPE universe
 */
define('DPSERVER_DPUNIVERSE_PATH', DPSERVER_ROOT_PATH . 'dpuniverse/');

/**
 * Path to the directory with server and universe settings
 */
define('DPSERVER_DPUNIVERSE_CONFIG_PATH', DPSERVER_ROOT_PATH . 'config/');

/**
 * Path to the library directory
 */
define('DPSERVER_DPUNIVERSE_LIB_PATH', DPSERVER_ROOT_PATH . 'lib/');

/**
 * Path to the file with the DutchPIPE Server class
 */
define('DPSERVER_DPSERVERCLASS_PATH', DPSERVER_DPUNIVERSE_LIB_PATH
    . 'dpserver.php');

/**
 * Path to the file with the DutchPIPE Universe class
 */
define('DPSERVER_DPUNIVERSECLASS_PATH', DPSERVER_DPUNIVERSE_LIB_PATH
    . 'dpuniverse.php');

/**
 * Path to the directory with HTML templates
 */
define('DPSERVER_DPUNIVERSE_TEMPLATE_PATH', DPSERVER_ROOT_PATH . 'template/');

/**
 * Type of socket, see http://www.php.net/socket_create
 * For *NIX, use AF_UNIX, for Windows, use AF_INET
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
 */
define('DPSERVER_SOCKET_ADDRESS', '127.0.0.1');
define('DPSERVER_SOCKET_PORT', '3333');

/**
 * The maximum of backlog incoming connections queued for processing
 *
 * Used by socket_listen in dpserver.php. See http://www.php.net/socket_listen
 */
define('DP_SERVER_MAX_SOCKET_BACKLOG', 5);

/**
 * The maximum number of seconds the server can stay up, 0 for no limit
 */
define('DPSERVER_MAXUPTIME', 0);

/**
 * The timezone we're in using an identifier as definied by
 * http://www.php.net/manual/en/timezones.php
 */
define('DPSERVER_TIMEZONE', 'Europe/Amsterdam');

/**
 * Which errors to report according to
 * http://www.php.net/manual/en/ref.errorfunc.php#errorfunc.constants
 */
define('DPSERVER_ERROR_REPORTING', E_ALL | E_STRICT);

/**
 * Maximum number of bytes dpclient.php can read from dpserver.php per chunk
 */
define('DPSERVER_CLIENT_CHUNK', 2048);

/**
 * Maximum number of bytes dpserver.php can read from dpclient.php per chunk
 */
define('DPSERVER_SERVER_CHUNK', 2048);
?>
