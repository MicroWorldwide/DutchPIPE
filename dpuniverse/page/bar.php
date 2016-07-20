<?php
/**
 * Dutchy's Bar
 *
 * DutchPIPE version 0.4; PHP version 5
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
 * @version    Subversion: $Id: bar.php 278 2007-08-19 22:52:25Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * Dutchy's Bar
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
final class Bar extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dp_text("Dutchy's Bar");
        $this->body = '<b>' . dp_text("Dutchy's Bar") . '</b><br />'
            . sprintf(dp_text('<p align="justify">This is Dutchy\'s Bar where
they serve cold beer. Today all beer is free, so be sure and grab your beer
before it\'s gone!</p><p align="justify">The exit to the <a href="%s">south</a>
leads back to the village square.</p>'),
            DPSERVER_CLIENT_URL . '?location=/page/square.php');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dp_text('Showcases'), DPUNIVERSE_PAGE_PATH
            . 'showcases.php'));
        $this->addExit(explode('#',
            dp_text('s#square#out#exit#leave#leave bar')),
            DPUNIVERSE_PAGE_PATH . 'square.php', NULL, array('bar_map',
            'square_area', 'rect', '100,270,300,300', dp_text('leave bar')),
            dp_text('leave bar'));
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        $this->makePresent(DPUNIVERSE_NPC_PATH . 'barkeeper.php');
    }
}
?>
