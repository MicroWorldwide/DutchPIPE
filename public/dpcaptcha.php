<?php
/**
 * Shows a CAPTCHA code image for a given database Id.
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage public
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpcaptcha.php 67 2006-06-30 22:43:03Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see         dpclient.php
 */

$config_dir = realpath(dirname(isset($_SERVER['SCRIPT_FILENAME'])
    ? $_SERVER['SCRIPT_FILENAME'] : __FILE__) . '/../config');

/**
 * Gets server settings
 */
require_once($config_dir . '/dpserver-ini.php');

/**
 * Gets universe settings
 */
require_once($config_dir . '/dpuniverse-ini.php');

/**
 * Gets I18N/L10 dptext functionality (if enabled)
 */
require_once(DPSERVER_LIB_PATH . 'dptext.php');

error_reporting(DPUNIVERSE_ERROR_REPORTING);

mysql_pconnect(DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER,
    DPUNIVERSE_MYSQL_PASSWORD)
    || die(sprintf(dptext('Could not connect: %s<br />'), mysql_error()));

mysql_select_db(DPUNIVERSE_MYSQL_DB)
    || die(sprintf(dptext('Failed to select database: %s<br />',
    DPUNIVERSE_MYSQL_DB)));

if (!isset($_GET) || !isset($_GET['captcha_id'])
        || !($result = mysql_query(
        "SELECT captchaFile FROM Captcha WHERE captchaId='"
        . $_GET['captcha_id'] . "'"))
        || FALSE === ($row = mysql_fetch_array($result))) {
    die(dptext('Failed to retrieve CAPTCHA image information.<br />'));
}

$captcha_image = file_get_contents(DPUNIVERSE_CAPTCHA_IMAGES_PATH . $row[0]);
if (FALSE === $captcha_image) {
    die(dptext('Failed to retrieve CAPTCHA image file.<br />'));
}

header('Content-Type: image/gif');
header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
echo $captcha_image;
?>
