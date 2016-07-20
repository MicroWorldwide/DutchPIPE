<?php
/**
 * 'Shopkeeper' class to create a shopkeeper
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_npc
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id$
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpNpc
 */

/**
 * Builts upon the standard DpNpc class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpNpc.php');

/**
 * Gets event constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'events.php');

/**
 * A shopkeeper
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_npc
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.0
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpNpc
 */
final class Shopkeeper extends DpNpc
{
    /**
     * Sets up the NPC at object creation time
     */
    public function createDpNpc()
    {
        // Standard setup calls:
        $this->addId(explode('#', dptext('shopkeeper')));
        $this->title = dptext('shopkeeper');
        $this->titleDefinite = dptext('the shopkeeper');
        $this->titleIndefinite = dptext('a shopkeeper');
        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'shopkeeper.gif';
        $this->body = '<img src="' . DPUNIVERSE_IMAGE_URL
            . 'shopkeeper_body.gif" width="85" height="200" border="0" alt="" '
            . 'align="left" style="margin-right: 15px" />'
            . dptext('A shopkeeper selling and buying items.<br />');
    }
}
?>
