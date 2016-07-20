<?php
/**
 * Constants with error return codes for DpObject::moveDpObject
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_include
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: move.php 45 2006-06-20 12:38:26Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject.php::moveDpObject()
 */

/**
 * Too heavy for destination
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_HEAVY', 1);

/**
 * Can't drop
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NODROP', 2);

/**
 * Can take it from its container
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOFROM', 3);

/**
 * Source object can't be inserted
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NOSRCINS', 4);

/**
 * Can't insert in destination object
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NODSTINS', 5);

/**
 * Can't pick the object up
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NOGET', 6);

/**
 * Bad destination
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_BADDEST', 7);

/**
 * We don't want to be moved
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOTSELF', 8);

/**
 * Environment won't let us go
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOTENV', 9);

/**
 * Destination won't accept us
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOTDEST', 10);
?>
