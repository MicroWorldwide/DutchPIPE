#!/usr/local/bin/php -q
<?php
/**
 * Creates and stores a random CAPTCHA image.
 * I run this from a cronjob to rotate 24 images.
 *
 * Based on code by Julien Crouzet, original header follows:
 *     >To use these functions, you'll need :
 *     >
 *     >GD Library http://fr3.php.net/manual/fr/ref.image.php
 *     >With FreeType 2 support --with-freetype-dir=DIR
 *     >
 *     >Image Magick http://www.imagemagick.org/script/index.php
 *
 * For additional details, see:
 * http://blog.theoconcept.com/index.php/2006/01/27/3-un-peu-tordu-comme-idee
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @author     Julien CROUZET <julien/ at /theoconcept.com>
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: make_captcha.php 22 2006-05-30 20:40:55Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver.php, /www/captcha.php
 */

error_reporting(E_ALL | E_STRICT);

require_once(realpath(dirname(__FILE__) . '/../config')
    . '/dpuniverse-ini.php');

mysql_pconnect(DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER,
    DPUNIVERSE_MYSQL_PASSWORD)
    || die(dptext("Could not connect: %s\n", mysql_error()));

mysql_select_db(DPUNIVERSE_MYSQL_DB)
    || die(dptext("Failed to select database: %s\n", DPUNIVERSE_MYSQL_DB));

/**
 * Get a string width and height with given parameters
 *
 * @author Julien CROUZET <julien/ at /theoconcept.com>
 * @param  string   The string
 * @param  string   Full path to TTF font file
 * @param  int      Font size
 * @return array    An array with [0] = Width [1] = Height
 */
function getstringdimensions($String, $Font, $FontSize)
{
    //putenv('GDFONTPATH=' . realpath(dirname($Font)));
    //$Font = basename($Font);
    $TextBox = imagettfbbox($FontSize, 0, $Font, $String);
    $Xlist = array($TextBox[0], $TextBox[2],$TextBox[4],$TextBox[6]);
    $Ylist = array($TextBox[1], $TextBox[3],$TextBox[5],$TextBox[7]);
    sort($Xlist);
    sort($Ylist);
    return (array(($Xlist[3] - $Xlist[0] + 10), ($Ylist[3] - $Ylist[0] + 10)));
}

/**
 * Build an animated swirled picture for form verifications
 *
 * @author Julien CROUZET <julien/ at /theoconcept.com>
 * @param  string   The string to show
 * @param  string   Full path to TTF font file
 * @param  int      Font size
 * @param  string   Background hex color code
 * @param  string   Text hex color code
 * @return string   Animated gif image binary content
 */
function distortion_string($String, $Font, $FontSize, $BGColor='#FFFFFF',
        $TextColor='#9999CC')
{
  // Font is here
  putenv('GDFONTPATH=' . realpath(dirname($Font)));
  $Font = basename($Font);

  // Image dimensions calculated from text
  list($Width, $Height) = getstringdimensions($String, $Font, $FontSize);

  // First, we create the source image with GD Image
  $ImageRessource = imagecreatetruecolor($Width, $Height)
    || die(dptext("Cannot Initialize new GD image stream"));

  // Translate color codes
  $Hex = '[0-9A-Fa-f]';
  if (preg_match("/^#?($Hex$Hex)($Hex$Hex)($Hex$Hex)$/", $BGColor, $Parts)) {
    $BGColors = array(hexdec($Parts[1]), hexdec($Parts[2]), hexdec($Parts[3]));
  } else {
    $BGColors = array(255, 255, 255);
  }
  if (preg_match("/^#?($Hex$Hex)($Hex$Hex)($Hex$Hex)$/", $TextColor, $Parts)) {
    $TextColors = array(hexdec($Parts[1]), hexdec($Parts[2]),
        hexdec($Parts[3]));
  } else {
    $TextColors = array(0, 0, 0);
  }

  // Colors allocations
  $BGColor = imagecolorallocate($ImageRessource, $BGColors[0], $BGColors[1],
    $BGColors[2]);
  $TextColor = imagecolorallocate($ImageRessource, $TextColors[0],
    $TextColors[1], $TextColors[2]);

  // We set the background
  imagefilledrectangle($ImageRessource, 0, 0, $Width, $Height, $BGColor);

  // We add the string
  imagettftext($ImageRessource, $FontSize, 0, 0, $Height - 8, $TextColor, $Font, $String);

  // Ad Imagick pecl extension doesn't allow to delay animated gifs (it seems)
  // let's do it in a dirty way (binary convert tool)

  // We save in files
  $TempFile = tempnam ("/tmp", "DISTORTION");
  $TempFile2 = tempnam ("/tmp", "DISTORTION");

  imagegif($ImageRessource, $TempFile);

  // We start building convert command
  $command = "/usr/local/bin/convert -delay 25 $TempFile";

  // First distortion part
  foreach (range(10, 160, 40) as $i) {
    $command .= " \\( -clone 0 -swirl $i \\)";
  }

  // The second ...
  foreach (range(140, -160, -40) as $i) {
    $command .= " \\( -clone 0 -swirl $i \\)";
  }

  // The third ...
  foreach (range(-140, 0, 40) as $i) {
    $command .= " \\( -clone 0 -swirl $i \\)";
  }

  // End of the command
  $command .= " -loop 0 $TempFile2";

  // Execute convert
  exec ($command, $output);

  // And read the result
  $Picture = file_get_contents($TempFile2);

  // Delete temporary files
  //unlink($TempFile);
  //unlink($TempFile2);
  return($Picture);
}

function get_rand_code()
{
    $length = mt_rand(5,6);

    // Letters and vars without i, l, 1, o, 0, 6, b, q, 9, 7 to avoid confusion:
    $letters = 'acdefghjkmnprstuvwxyz';
    $chars = 'acdefghjkmnprstuvwxyz23458';
    $char_length = strlen($chars) - 1;
    // Always start with a letter:
    $code = substr($letters, mt_rand(0, strlen($letters) - 1), 1);
    $length--;
    // Fill out with letters of numers:
    for ($i = 0; $i < $length; $i++) {
        $code .= substr($chars, mt_rand(0, $char_length), 1);
    }

    return $code;
}

$str = distortion_string(($code = get_rand_code()), DPUNIVERSE_SCRIPT_PATH
    . 'trebuc.ttf', 30, '#FFCF0F','#000066');
$file = "$code.gif";
$fp = fopen(DPUNIVERSE_CAPTCHA_IMAGES_PATH . $file, 'w');
fwrite($fp, $str);
fclose($fp);
chown(DPUNIVERSE_CAPTCHA_IMAGES_PATH . $file, DPUNIVERSE_FILE_OWNER);
echo sprintf(dptext("Created %s.gif\n"), $code);

$oldest_time = time() + 3600; /* Future */
$oldest_file = FALSE;
$count = 0;

$d = dir(DPUNIVERSE_CAPTCHA_IMAGES_PATH);
while (false !== ($entry = $d->read())) {
    if ($entry !== '.' && $entry !== '..' && strlen($entry) > 4
            && substr($entry, -4) == '.gif') {
        $count++;
        $mtime = filemtime(DPUNIVERSE_CAPTCHA_IMAGES_PATH . $entry);
        if ($mtime < $oldest_time) {
            $oldest_time = $mtime;
            $oldest_file = $entry;
        }
    }
}
if ($count > 24 && FALSE !== $oldest_file) {
    unlink(DPUNIVERSE_CAPTCHA_IMAGES_PATH . $oldest_file);
    echo "Removed: $oldest_file\n";
}

$query = "SELECT captchaId, captchaFile, captchaTimestamp FROM Captcha "
    . "ORDER BY captchaTimestamp";
$result = mysql_query($query);
if ($result) {
    $num_rows = mysql_num_rows($result);
    while ($num_rows-- >= 24 && FALSE !== ($row = mysql_fetch_array($result))) {
        $captcha_id = $row[0];
        mysql_query("DELETE from Captcha where captchaId='{$captcha_id}'");
    }
}

mysql_query("INSERT INTO Captcha (captchaFile) VALUES ('$file')");
?>

