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
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: move.php 170 2007-06-08 21:26:55Z ls $
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
define('E_MOVEOBJECT_VOLUME', 2);

/**
 * Can't drop
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NODROP', 3);

/**
 * Can't take it from its container
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOFROM', 4);

/**
 * Source object can't be inserted
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NOSRCINS', 5);

/**
 * Can't insert in destination object
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NODSTINS', 6);

/**
 * Can't pick the object up
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_NOGET', 7);

/**
 * Bad destination
 *
 * @see        DpObject.php::moveDpObject()
 */
define('E_MOVEOBJECT_BADDEST', 8);

/**
 * We don't want to be moved
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOTSELF', 9);

/**
 * Environment won't let us go
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOTENV', 10);

/**
 * Destination won't accept us
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_NOTDEST', 11);

/**
 * Illegal amount was given for a heap (for instance more credits than you have)
 *
 * Not used yet.
 *
 * @ignore
 */
define('E_MOVEOBJECT_BADHEAP', 12);
?>
