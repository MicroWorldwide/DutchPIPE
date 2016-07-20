<?php
/**
 * 'Shopkeeper' class to create a shopkeeper
 *
 * DutchPIPE version 0.4; PHP version 5
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
 * @version    Subversion: $Id: shopkeeper.php 308 2007-09-02 19:18:58Z ls $
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
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
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
        $this->addId(explode('#', dp_text('shopkeeper')));
        $this->title = dp_text('shopkeeper');
        $this->titleDefinite = dp_text('the shopkeeper');
        $this->titleIndefinite = dp_text('a shopkeeper');
        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'shopkeeper.gif';
        $this->titleImgWidth = 58;
        $this->titleImgHeight = 100;
        $this->body = '<img src="' . DPUNIVERSE_IMAGE_URL
            . 'shopkeeper_body.gif" width="115" height="200" border="0" alt="" '
            . 'align="left" style="margin-right: 15px" />'
            . dp_text('A shopkeeper selling and buying items.<br />');
    }
}
?>
