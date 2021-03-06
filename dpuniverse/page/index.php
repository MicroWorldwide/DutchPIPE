<?php
/**
 * The Home page
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
 * @version    Subversion: $Id: index.php 278 2007-08-19 22:52:25Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * The Home page
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
final class Index extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        $this->title = dp_text('Home');
        $this->setBody(dp_text(DPUNIVERSE_PAGE_PATH . 'index.html'), 'file');

        // Alternative verbs to go to another page, also used by wandering NPCs:
        $this->addExit(dp_text('about'), DPUNIVERSE_PAGE_PATH . 'about.php');
        $this->addExit(dp_text('showcases'), DPUNIVERSE_PAGE_PATH . 'showcases.php');
        $this->addExit(dp_text('faq'), DPUNIVERSE_PAGE_PATH . 'faq.php');
        $this->addExit(dp_text('copyright'), DPUNIVERSE_PAGE_PATH . 'copyright.php');
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        // Creates a note, moves it here:
        $this->makePresent(DPUNIVERSE_OBJ_PATH . 'note.php');

        // Creates mobile NPC, moves it here:
        $this->makePresent(DPUNIVERSE_NPC_PATH . 'mobile.php', 1, FALSE);
    }
}
?>
