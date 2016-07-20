<?php
/**
 * The Home page
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
 * @version    Subversion: $Id: index.php 22 2006-05-30 20:40:55Z ls $
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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
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
        $this->setTitle(dptext('Home'));
        $this->setBody(dptext(DPUNIVERSE_PAGE_PATH . 'index.html'), 'file');

        $this->addExit(dptext('test1'), DPUNIVERSE_PAGE_PATH . 'test1.php');
        $this->addExit(dptext('test2'), DPUNIVERSE_PAGE_PATH . 'test2.php');

        // Creates a note, moves it here:
        $note = get_current_dpuniverse()->newDpObject(DPUNIVERSE_OBJ_PATH
            . 'note.php');
        $note->moveDpObject($this);

        // Creates mobile NPC, moves it here:
        $marvin = get_current_dpuniverse()->newDpObject(DPUNIVERSE_NPC_PATH
            . 'mobile.php');
        $marvin->moveDpObject($this);
    }
}
?>
