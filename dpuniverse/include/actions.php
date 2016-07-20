<?php
/**
 * Constants for the action system
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
 * @version    Subversion: $Id: actions.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
define('DP_ACTION_OPERANT_MENU',           1);
define('DP_ACTION_OPERANT_NONE',           2);
define('DP_ACTION_OPERANT_COMPLETE',       4);
define('DP_ACTION_OPERANT_METHOD',         8); // Not supported yet

define('DP_ACTION_TARGET_SELF',            1);
define('DP_ACTION_TARGET_LIVING',          2);
define('DP_ACTION_TARGET_OBJINV',          4);
define('DP_ACTION_TARGET_OBJENV',          8);
define('DP_ACTION_TARGET_METHOD',         16); // Not supported yet

define('DP_ACTION_AUTHORIZED_GUEST',       1); // Not supported yet
define('DP_ACTION_AUTHORIZED_REGISTERED',  2);
define('DP_ACTION_AUTHORIZED_ADMIN',       4);
define('DP_ACTION_AUTHORIZED_METHOD',      8); // Not supported yet
define('DP_ACTION_AUTHORIZED_ALL',        16);

define('DP_ACTION_SCOPE_ENVIRONMENT',      1);
define('DP_ACTION_SCOPE_INVENTORY',        2);
define('DP_ACTION_SCOPE_SELF',             4);
define('DP_ACTION_SCOPE_METHOD',           8); // Not supported yet
define('DP_ACTION_SCOPE_ALL',             16);
?>
