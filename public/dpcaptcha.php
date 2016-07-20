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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpcaptcha.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see         dpclient.php
 */

require_once('../config/dpuniverse-ini.php');

error_reporting(DPUNIVERSE_ERROR_REPORTING);

mysql_pconnect(DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER, DPUNIVERSE_MYSQL_PASSWORD)
    || die('Could not connect: ' . mysql_error() . "\n");

mysql_select_db(DPUNIVERSE_MYSQL_DB)
    || die('Failed to select database: ' . DPUNIVERSE_MYSQL_DB . "\n");

if (!isset($_GET) || !isset($_GET['captcha_id'])
        || !($result = mysql_query("SELECT captchaFile from Captcha where captchaId='" . $_GET['captcha_id'] . "'"))
        || FALSE === ($row = mysql_fetch_array($result))) {
    die("Failed to retrieve CAPTCHA image information.\n");
}

$captcha_image = file_get_contents(DPUNIVERSE_CAPTCHA_IMAGES_PATH . $row[0]);
if (FALSE === $captcha_image) {
    die("Failed to retreieve CAPTCHA image file.\n");
}

header('Content-Type: image/gif');
header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
echo $captcha_image;
?>