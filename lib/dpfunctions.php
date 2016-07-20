<?php
/**
 * Common functions available to all objects in the universe
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage lib
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpfunctions.php 77 2006-07-13 20:39:04Z ls $
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
    require_once(DPUNIVERSE_PREFIX_PATH . $path);
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
?>
