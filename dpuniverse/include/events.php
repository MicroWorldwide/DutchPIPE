<?php
/**
 * Constants for the events system
 *
 * For some events, the event() method is called in objects, if defined. The
 * first argument is the type of event, as defined by the constants in this
 * file. Other arguments depend on the type of event.
 *
 * DutchPIPE version 0.3; PHP version 5
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
 * @version    Subversion: $Id: events.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

/**
 * An object left this object's inventory
 *
 * When an objects leaves an inventory, the following call takes place:
 * $old_environment->event(EVENT_LEFT_INV, $object, $new_environment);
 *
 * @see        EVENT_ENTERED_INV, EVENT_ENTERED_ENV, EVENT_CHANGED_ENV,
 *             EVENT_DESTROYING_OBJ, DpObject::moveDpObject(),
 *             DpObject::removeDpObject()
 */
define('EVENT_LEFT_INV', 1);

/**
 * An object entered this object's inventory
 *
 * When an objects enters a new environment, the following call takes place:
 * $new_environment->event(EVENT_ENTERED_INV, $object, $old_environment);
 *
 * @see        EVENT_LEFT_INV, EVENT_ENTERED_ENV, EVENT_CHANGED_ENV,
 *             EVENT_DESTROYING_OBJ, DpObject::moveDpObject()
 */
define('EVENT_ENTERED_INV', 2);

/**
 * An object entered this object's environment
 *
 * When an objects enters a new environment, the following call takes place in
 * all objects in the new environment's inventory:
 * $inventory_object->event(EVENT_ENTERED_ENV, $object, $old_environment);
 *
 * @see        EVENT_LEFT_INV, EVENT_ENTERED_INV, EVENT_CHANGED_ENV,
 *             EVENT_DESTROYING_OBJ, DpObject::moveDpObject()
 */
define('EVENT_ENTERED_ENV', 3);

/**
 * An object's environment changed
 *
 * When an objects enters a new environment, the following call takes place in
 * the object:
 * $object->event(EVENT_CHANGED_ENV, $old_environment, $new_environment);
 *
 * @see        EVENT_LEFT_INV, EVENT_ENTERED_INV, EVENT_ENTERED_ENV,
 *             EVENT_DESTROYING_OBJ, DpObject::moveDpObject(),
 *             DpObject::removeDpObject()
 */
define('EVENT_CHANGED_ENV', 4);

/**
 * An object is being destroyed
 *
 * When an objects is being destroyed, the following call takes place in the
 * object:
 * $object->event(EVENT_DESTROYING_OBJ);
 *
 * @see        EVENT_LEFT_INV, EVENT_ENTERED_INV, EVENT_ENTERED_ENV,
 *             EVENT_CHANGED_ENV, DpObject::removeDpObject()
 */
define('EVENT_DESTROYING_OBJ', 5);
?>
