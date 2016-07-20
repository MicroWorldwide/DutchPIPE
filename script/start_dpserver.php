#!/usr/local/bin/php -q
<?php
/**
 * Starts the DutchPIPE server
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage script
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: start_dpserver.php 162 2007-06-05 22:57:57Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver.php, dpserver-ini.php, dpuniverse.php,
 *             dpuniverse-ini.php
 */

/**
 * Gets constants with server settings
 */
require_once(realpath(dirname(__FILE__) . '/..') . '/config/dpserver-ini.php');

/**
 * Gets DutchPIPE Server class
 */
require_once(DPSERVER_DPSERVERCLASS_PATH);

/**
 * Gets DutchPIPE Universe class
 */
require_once(DPSERVER_DPUNIVERSECLASS_PATH);

/* Shows all possible errors */
error_reporting(DPSERVER_ERROR_REPORTING);

/* Creates a new, unstarted server */
$gDpServer = new DpServer(DPSERVER_DPUNIVERSE_CONFIG_PATH . 'dpserver-ini.php');

/* Creates a new universe */
$gDpUniverse = new DpUniverse(DPSERVER_DPUNIVERSE_CONFIG_PATH .
    'dpuniverse-ini.php');

/* Starts the DutchPIPE server with the freshly created universe */
$gDpServer->runDpServer($gDpUniverse);
?>
