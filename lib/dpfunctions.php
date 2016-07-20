<?php
/**
 * Common functions available to all objects in the universe
 *
 * DutchPIPE version 0.4; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage lib
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpfunctions.php 311 2007-09-03 12:48:09Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php, dptemplates.php
 */

/**
 * Gets the current user connected to the server
 *
 * If a user page or AJAX request caused the current chain of execution that
 * caused this function to be called, that user object is returned. Otherwise
 * FALSE is returned. For example, if the chain of execution if caused by a
 * setTimeout, this will return FALSE.
 *
 * @return     object    Reference to user object of FALSE for no current user
 */
function &get_current_dpuser()
{
    global $grCurrentDpUniverse;

    return $grCurrentDpUniverse->getCurrentDpUser();
}

/**
 * Gets the current object performing an action
 *
 * This can also be a computer controlled character. If the chain of execution
 * that caused this function to be called isn't the result of a user or npc
 * performing an action, FALSE is returned.
 *
 * If the current object is a user performing an action, get_current_dpobject
 * doesn't necessarily equal get_current_dpuser. Someone or something might
 * force the user the perform an action.
 *
 * @return     object    Reference to user object of FALSE for no current user
 */
function &get_current_dpobject()
{
    global $grCurrentDpObject;

    return $grCurrentDpObject;
}

/**
 * Gets the current universe handling the current user
 *
 * The universe object keeps track of all objects and provides methods such
 * as newDpObject. Usually and currently there's just the one universe object.
 * In the future there might be more, for instance to allow users to switch
 * between different worlds without having to log in again.
 *
 * @return     object    Reference to user object of FALSE for no current user
 */
function &get_current_dpuniverse()
{
    global $grCurrentDpUniverse;

    return $grCurrentDpUniverse;
}

/**
 * Initializes a new DutchPIPE property in an object
 *
 * Initializes a property which can be accessed in various ways.
 *
 * @param      mixed     $value      the initial value of the property if any
 * @param      string    $setter     optional method to call when setting value
 * @param      string    $getter     optional method to clal for retreival
 * @return     array     A custom data structure, don't tamper with it
*/
function new_dp_property($value = NULL, $setter = NULL, $getter = NULL)
{
    return array('new_dp_property', $value, $setter, $getter);
}

/**
 * Includes universe object
 *
 * This is a wrapper around require_once. You can only inherit in
 * DPUNIVERSE_PREFIX_PATH (dpuniverse/ in the standard distribution), which is
 * the top directory for this function. Use the constants from
 * config/dpuniverse.ini to form paths.
 *
 * Examples (from dpuniverse/page/login.php):
 * inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');
 * inherit(DPUNIVERSE_INCLUDE_PATH . 'events.php');
 *
 * @param      string    $path       path to class file within dpuniverse/
 */
function inherit($path)
{
    /* PHP 5.1.5 kludge symlink bug fix */
    $files = get_included_files();
    $pos = dp_strrpos($path, '/');
    if (FALSE !== $pos && $pos != dp_strlen($path) -1) {
        $file = dp_substr($path, $pos + 1);
    }
    foreach ($files as $f) {
        $pos = dp_strrpos($f, '/');
        if (FALSE !== $pos && $pos != dp_strlen($f) -1) {
            if ($file === dp_substr($f, $pos + 1)) {
                return;
            }
        }
    }

    require_once(DPUNIVERSE_PREFIX_PATH . $path);
}

/**
 * Verifies that the contents of a variable can be called as a method
 *
 * Identical to the PHP {@link http://www.php.net/is_callable is_callable}
 * method, except that it can be not be the name of a function stored in a
 * string variable, it must be an object and the name of a method within the
 * object, like this:
 *
 *     array($SomeObject, 'MethodName')
 *
 * @param      mixed     &$var        variable to check
 * @return     boolean   TRUE if var is callable, FALSE otherwise
 * @see        parse_dp_callable
 */
function is_dp_callable(&$var)
{
    return is_array($var) && is_callable($var);
}

/**
 * Replaces the contents of a callable variable
 *
 * Checks if the given variable can be called as a method with is_dp_callable.
 * If it is, calls the method and replaces the variable with the result.
 *
 * @param      mixed     &$var        variable to check
 * @see        is_dp_callable
 */
function parse_dp_callable(&$var)
{
    if (!is_dp_callable($var)) {
        return;
    }

    $nr_of_args = func_num_args() - 1;
    if (1 === $nr_of_args) {
        $var = $var[0]->{$var[1]}();
        return;
    }

    $args = array_slice(func_get_args(), 1);
    $var = call_user_func_array(array($var[0], $var[1]), $args);
}

/**
 * Reads entire file within the universe directory into a string
 *
 * Reads and returns the given file. The file should be within the directory
 * defined by DPUNIVERSE_PATH, for example /page/foo.txt. When translations
 * are enabled an alternative file can be created and used instead. For this to
 * work, the file must be placed in the directory defined by DPSERVER_LOCALE in
 * the directory defined by DPSERVER_GETTEXT_LOCALE_PATH, for example
 * /home/dutchpipe/locale/nl_NL/page/foo.txt.
 *
 * @param      string    $path        path within the universe directory
 * @return     mixed     string with file contents or FALSE on failure
 * @see        DPUNIVERSE_PATH, DPSERVER_GETTEXT_ENABLED, DPSERVER_LOCALE,
 8             DPSERVER_GETTEXT_LOCALE_PATH, dptext
 */
function dp_file_get_contents($path)
{
    return file_get_contents(
        (DPSERVER_GETTEXT_ENABLED && DPSERVER_LOCALE != '0'
        && file_exists(DPSERVER_GETTEXT_LOCALE_PATH . DPSERVER_LOCALE . $path)
        ? DPSERVER_GETTEXT_LOCALE_PATH . DPSERVER_LOCALE
        : DPUNIVERSE_PREFIX_PATH)
        . $path);
}

/**
 * Gets a 32-character random string
 *
 * @param      string    $length     optional different length <= 32
 * @return     string    random characters
 */
function make_random_id($base = 16)
{
    $id = md5(uniqid(rand(), true));
    return 16 === $base ? $id : base_convert($id, 16, $base);
}

/**
 * Is the given integer or string a whole positive number?
 *
 * @param      mixed     $var        the integer of string to check
 * @return     boolean   TRUE for a whole positive number, FALSE otherwise
 */
function is_whole_number($var)
{
   $var = (string)$var;

   for ($i = 0, $len = dp_strlen($var); $i < $len; $i++) {
       if (($ascii_code = ord($var[$i])) < 48 || $ascii_code > 57) {
           return FALSE;
       }
   }
   return TRUE;
}

/**
 * Gets a short, descriptive string for an age in seconds
 *
 * This method is used for example to show the age of users. A new user could
 * just be "1 minute and 15 seconds" old, while addicts could be hanging around
 * for "2 years, 74 days and 20 hours".
 *
 * @param      int       $age        number of seconds
 * @return     string    Short, descriptive age string
 */
function get_age2string($age)
{
    $rest_age= $age;

    $rval = array();
    if ($age >= 31536000) {
        $tmp = floor($age / 31536000);
        $rval[] = 1 === $tmp ? dp_text('1 year') : dp_text('%d years', $tmp);
        $rest_age = $age % 31536000;
    }
    if ($rest_age >= 86400) {
        $tmp = floor($rest_age / 86400);
        $rval[] = 1 === $tmp ? dp_text('1 day') : dp_text('%d days', $tmp);
        $rest_age = $rest_age % 86400;
    }
    if ($rest_age >= 3600) {
        $tmp = floor($rest_age / 3600);
        $rval[] = 1 === $tmp ? dp_text('1 hour') : dp_text('%d hours', $tmp);
        $rest_age = $rest_age % 3600;
    }

    if ($age < 31536000) {
        if ($rest_age >= 60) {
            $tmp = floor($rest_age / 60);
            $rval[] = 1 === $tmp ? dp_text('1 minute')
                : dp_text('%d minutes', $tmp);
            $rest_age = $rest_age % 60;
        }
        if ($age < 86400) {
            $rval[] = 1 === $rest_age ? dp_text('1 second')
                : dp_text('%d seconds', $rest_age);
        }
    }

    if (1 === ($sz = count($rval))) {
        return $rval[0];
    }

    $last = $rval[$sz - 1];
    $rval = array_slice($rval, 0, -1);

    return implode(', ', $rval) . ' ' . dp_text('and') . ' ' . $last;
}

/**
 * Copies and processes an uploaded image for a given object
 *
 * Copies an image for a given user with the given file path to a given
 * destination. Resizes the image if the width in pixels exceeds
 * DPSERVER_OBJECT_IMAGE_MAX_WIDTH and/or the height exceeds
 * DPSERVER_OBJECT_IMAGE_MAX_HEIGHT. The image type must defined in
 * DPSERVER_OBJECT_IMAGE_VALID_TYPES ("gif", "jpg" or "png" by default).
 *
 * @param      string    &$object   user who will use yhis avatar
 * @param      string    $from      path to uploaded avatar image
 * @param      string    $to        path of copied/resulting image
 * @return     boolean   TRUE for success, error string for failure
 * @see        DpUser::actionAvatar, DPSERVER_OBJECT_IMAGE_MAX_WIDTH,
 *             DPSERVER_OBJECT_IMAGE_MAX_HEIGHT,
 *             DPSERVER_OBJECT_IMAGE_VALID_TYPES
 * @since      DutchPIPE 0.4.1
 */
function dp_upload_image(&$object, $from, $to)
{
    if (!DPUNIVERSE_AVATAR_CUSTOM_ENABLED || !function_exists('gd_info')) {
        return dp_text("Image upload is not enabled.");
    }

    $valid_types = explode(',', DPSERVER_OBJECT_IMAGE_VALID_TYPES);
    if (!is_array($image_info = getimagesize($from))) {
        $err = TRUE;
    } else {
        $type = image_type_to_extension($image_info[2], FALSE);

        if (!in_array($type, $valid_types)
                && !($type === 'jpg' && in_array('jpeg', $valid_types))
                && !($type === 'jpeg' && in_array('jpg', $valid_types))) {
            $err = TRUE;
        }
    }
    if (isset($err)) {
        return sprintf(dp_text('Invalid file type. The type of image must be one of the following: %s.'),
            implode(dp_text(', '), $valid_types));
    }

    $copy_result = copy($from, $to);
    @unlink($from);
    if (TRUE !== $copy_result) {
        return dp_text("Failed to upload image.");
    }

    list($width, $height) = $image_info;

    // No resizing needed?
    if ($width <= DPSERVER_OBJECT_IMAGE_MAX_WIDTH
            && $height <= DPSERVER_OBJECT_IMAGE_MAX_HEIGHT) {
        $object->titleImgWidth = $width;
        $object->titleImgHeight = $height;

        return TRUE;
    }

    // Resize image: determine new dimensions first
    $new_width = $width;
    $new_height = $height;
    if ($new_width > DPSERVER_OBJECT_IMAGE_MAX_WIDTH) {
        $new_height = round($new_height * DPSERVER_OBJECT_IMAGE_MAX_WIDTH
            / $new_width);
        $new_width = DPSERVER_OBJECT_IMAGE_MAX_WIDTH;
    }
    if ($new_height > DPSERVER_OBJECT_IMAGE_MAX_HEIGHT) {
        $new_width = round($new_width * DPSERVER_OBJECT_IMAGE_MAX_HEIGHT
            / $new_height);
        $new_height = DPSERVER_OBJECT_IMAGE_MAX_HEIGHT;
    }

    // Resize image: resample with new dimensions
    $image_p = imagecreatetruecolor($new_width, $new_height);
    switch ($type) {
        case 'gif':
            $image = imagecreatefromgif($to);
            $transparentIndex = imagecolortransparent($image);
            $trans_colors = imagecolorsforindex($image, $transparentIndex);
            $trans_color = imagecolorallocate($image, $trans_colors['red'],
                $trans_colors['green'], $trans_colors['blue']);
            imagecolortransparent($image, $trans_color);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width,
                $new_height, $width, $height);
            $trans_color = imagecolorallocate($image_p,
                $trans_colors['red'], $trans_colors['green'],
                $trans_colors['blue']);
            imagecolortransparent($image_p, $trans_color);
            imagegif($image_p, $to);
            break;
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($to);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width,
            $new_height, $width, $height);
            $sharpenMatrix = array(array(-1, -1, -1), array(-1, 16, -1),
                array(-1, -1, -1));
            $divisor = 8;
            $offset = 0;
            imageconvolution($image_p, $sharpenMatrix, $divisor, $offset);
            imagejpeg($image_p, $to, 90);
            break;
        case 'png':
            $image = imagecreatefrompng($to);
            imageantialias($image_p, TRUE);
            imagealphablending($image_p, FALSE);
            imagesavealpha($image_p, TRUE);
            $transparent = imagecolorallocatealpha($image_p, 255, 255, 255,
                127);
            imagefilledrectangle($image_p, 0, 0, $new_width, $new_height,
                $transparent);
            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width,
                $new_height, $width, $height);
            imagepng($image_p, $to, 0);
            break;
        default:
            unlink($to);
            return dp_text("Failed to upload image because of a configuration error.");
    }

    $object->titleImgWidth = $new_width;
    $object->titleImgHeight = $new_height;

    return TRUE;
}
?>
