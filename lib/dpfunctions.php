<?php
/**
 * Common functions available to all objects in the universe
 *
 * DutchPIPE version 0.2; PHP version 5
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
 * @version    Subversion: $Id: dpfunctions.php 238 2007-07-08 15:40:07Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php
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
    $pos = strrpos($path, '/');
    if (FALSE !== $pos && $pos != strlen($path) -1) {
        $file = substr($path, $pos + 1);
    }
    foreach ($files as $f) {
        $pos = strrpos($f, '/');
        if (FALSE !== $pos && $pos != strlen($f) -1) {
            if ($file === substr($f, $pos + 1)) {
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
 * Gets a 32-character random string
 *
 * @return     string    32 random characters
 */
function make_random_id()
{
    return md5(uniqid(rand(), true));

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

   for ($i = 0, $len = strlen($var); $i < $len; $i++) {
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
        $rval[] = 1 === $tmp ? dptext('1 year') : dptext('%d years', $tmp);
        $rest_age = $age % 31536000;
    }
    if ($rest_age >= 86400) {
        $tmp = floor($rest_age / 86400);
        $rval[] = 1 === $tmp ? dptext('1 day') : dptext('%d days', $tmp);
        $rest_age = $rest_age % 86400;
    }
    if ($rest_age >= 3600) {
        $tmp = floor($rest_age / 3600);
        $rval[] = 1 === $tmp ? dptext('1 hour') : dptext('%d hours', $tmp);
        $rest_age = $rest_age % 3600;
    }

    if ($age < 31536000) {
        if ($rest_age >= 60) {
            $tmp = floor($rest_age / 60);
            $rval[] = 1 === $tmp ? dptext('1 minute')
                : dptext('%d minutes', $tmp);
            $rest_age = $rest_age % 60;
        }
        if ($age < 86400) {
            $rval[] = 1 === $rest_age ? dptext('1 second')
                : dptext('%d seconds', $rest_age);
        }
    }

    if (1 === ($sz = count($rval))) {
        return $rval[0];
    }

    $last = $rval[$sz - 1];
    $rval = array_slice($rval, 0, -1);

    return implode(', ', $rval) . ' ' . dptext('and') . ' ' . $last;
}
?>
