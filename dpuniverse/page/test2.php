<?php
/**
 * The second test page
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: test2.php 45 2006-06-20 12:38:26Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * The second test page
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */
final class Test2 extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->setTitle(dptext('Test Page 2'));
        $this->setBody(dptext('This is Dutchy\'s Bar where they serve cold
beer. Today all beer is free, so be sure and grab your beer before it\'s gone!<br />'));
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, '/'),
            dptext('Test Page 2'));

        // Creates a barkeeper, moves it here:
        $barkeeper = get_current_dpuniverse()->newDpObject(DPUNIVERSE_NPC_PATH
            . 'barkeeper.php');
        $barkeeper->moveDpObject($this);
    }
}
?>
