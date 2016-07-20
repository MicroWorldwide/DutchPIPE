<?php
/**
 * Constants for the action system
 *
 * Used by the {@link Dpobject::addAction} and {@link DpObject::getActions}
 * methods.
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
 * @version    Subversion: $Id: actions.php 45 2006-06-20 12:38:26Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject::addAction(), DpObject::getActions()
 */

/**
 * Action operates on object carrying action menu
 *
 * The noun associated with the action verb becomes the object that carries the
 * action in its action menu.
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_OPERANT_NONE, DP_ACTION_OPERANT_COMPLETE
 */
define('DP_ACTION_OPERANT_MENU', 1);

/**
 * Action has no operant or operant is current living
 *
 * There is no noun associated with the action verb, for example "laugh".
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_OPERANT_MENU, DP_ACTION_OPERANT_COMPLETE
 */
define('DP_ACTION_OPERANT_NONE', 2);

/**
 * Action operates on object carrying action menu and on to be given operant
 *
 * Can be used to ask the user on who or what the action must be performed, for
 * example "give".
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_OPERANT_MENU, DP_ACTION_OPERANT_NONE
 */
define('DP_ACTION_OPERANT_COMPLETE', 4);

/**
 * Not supported yet
 *
 * @ignore
 */
define('DP_ACTION_OPERANT_METHOD', 8);

/**
 * Action appears on this objects' action menu
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_TARGET_LIVING, DP_ACTION_TARGET_OBJINV,
 *             DP_ACTION_TARGET_OBJENV
 */
define('DP_ACTION_TARGET_SELF', 1);

/**
 * Action appears on livings' action menu in this objects environment
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_TARGET_SELF, DP_ACTION_TARGET_OBJINV,
 *             DP_ACTION_TARGET_OBJENV
 */
define('DP_ACTION_TARGET_LIVING', 2);

/**
 * Action appears on action menu of objects in inventory of this object
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_TARGET_SELF, DP_ACTION_TARGET_LIVING,
 *             DP_ACTION_TARGET_OBJENV
 */
define('DP_ACTION_TARGET_OBJINV', 4);

/**
 * Action appears on action menu of objects in environment of this object
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_TARGET_SELF, DP_ACTION_TARGET_LIVING,
 *             DP_ACTION_TARGET_OBJINV
 */
define('DP_ACTION_TARGET_OBJENV', 8);

/**
 * Not supported yet
 *
 * @ignore
 */
define('DP_ACTION_TARGET_METHOD', 16);

/**
 * The action is restricted to guests only
 *
 * Not supported yet.
 *
 * @ignore
 */
define('DP_ACTION_AUTHORIZED_GUEST', 1);

/**
 * The action is restricted to registered users only
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_AUTHORIZED_ALL
 */
define('DP_ACTION_AUTHORIZED_REGISTERED', 2);

/**
 * The action is restricted to administrator users only
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_AUTHORIZED_REGISTERED, DP_ACTION_AUTHORIZED_ALL
 */
define('DP_ACTION_AUTHORIZED_ADMIN', 4);

/**
 * The action is restricted by a method
 *
 * Not supported yet.
 *
 * @ignore
 */
define('DP_ACTION_AUTHORIZED_METHOD', 8);

/**
 * Everybody may try to perform the action
 *
 * The method that is called may forbid it anyway though.
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_AUTHORIZED_REGISTERED, DP_ACTION_AUTHORIZED_ADMIN
 */
define('DP_ACTION_AUTHORIZED_ALL', 16);

/**
 * Action is available to objects in this object's environment only
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_SCOPE_INVENTORY, DP_ACTION_SCOPE_SELF,
 *             DP_ACTION_SCOPE_ALL
 */
define('DP_ACTION_SCOPE_ENVIRONMENT', 1);

/**
 * Action is available to objects in this object's inventory only
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_SCOPE_ENVIRONMENT, DP_ACTION_SCOPE_SELF,
 *             DP_ACTION_SCOPE_ALL
 */
define('DP_ACTION_SCOPE_INVENTORY', 2);

/**
 * Action is available to this object only
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_SCOPE_ENVIRONMENT, DP_ACTION_SCOPE_INVENTORY,
 *             DP_ACTION_SCOPE_ALL
 */
define('DP_ACTION_SCOPE_SELF', 4);

/**
 * Action is available to objects returned by a method only
 *
 * Not supported yet.
 *
 * @ignore
 */
define('DP_ACTION_SCOPE_METHOD', 8);

/**
 * Action is available to this object, inventory and objects in environment
 *
 * @see        DpObject::addAction(), DpObject::getActions(),
 *             DP_ACTION_SCOPE_ENVIRONMENT, DP_ACTION_SCOPE_INVENTORY,
 *             DP_ACTION_SCOPE_SELF
 */
define('DP_ACTION_SCOPE_ALL', 16);
?>
