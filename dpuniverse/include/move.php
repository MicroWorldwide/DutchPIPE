<?php
/**
 * Provides named constants with error codes for DpObject::moveDpObject
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
 * @version    Subversion: $Id: move.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject.php
 */

define('E_MOVEOBJECT_HEAVY',         1);       /* Too heavy for destination */
define('E_MOVEOBJECT_NODROP',        2);       /* Can't drop */
define('E_MOVEOBJECT_NOFROM',        3);       /* Can take it from its container */
define('E_MOVEOBJECT_NOSRCINS',      4);       /* Source object can't be inserted */
define('E_MOVEOBJECT_NODSTINS',      5);       /* Can't insert in destination object */
define('E_MOVEOBJECT_NOGET',         6);       /* Can't pick the object up */

define('E_MOVEOBJECT_BADDEST',       7);       /* Bad destination */
define('E_MOVEOBJECT_NOTSELF',       8);       /* We don't want to be moved */
define('E_MOVEOBJECT_NOTENV',        9);       /* environment won't let us go */
define('E_MOVEOBJECT_NOTDEST',       10);      /* destination won't accept us */
?>
