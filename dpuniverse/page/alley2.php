<?php
/**
 * A dark alley
 *
 * DutchPIPE version 0.2; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: alley2.php 243 2007-07-08 16:26:23Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * A dark alley
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */
final class Alley2 extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dptext('A dark alley');
        $this->body = '<img src="' . DPUNIVERSE_IMAGE_URL . 'alley2.jpg"
width="450" height="339" border="0" usemap="#alley2_map" alt=""
style="border: solid 1px black; margin-right: 10px" title="" alt=""
align="left" />
<div style="width: 220px; float: left">
<br /><b>'
            . dptext('A Dark Alley') . '</b><br />'
            . sprintf(dptext('<p align="justify">You are in a dark alley.
Better get out of here quick.</p><p align="justify">To the
<a href="%s">southeast</a> you see a square.</p>'),
            DPSERVER_CLIENT_URL . '?location=/page/square.php')
            . '</div><br clear="all" />';
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dptext('Showcases'), DPUNIVERSE_PAGE_PATH . 'showcases.php'));
        $this->addExit(array(dptext('se'), dptext('square')),
            DPUNIVERSE_PAGE_PATH . 'square.php', NULL,
            array('alley2_map', 'square_area', 'rect', '165,303,450,339'));
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        $this->makePresent(DPUNIVERSE_NPC_PATH . 'drunk.php');
    }
}
?>
