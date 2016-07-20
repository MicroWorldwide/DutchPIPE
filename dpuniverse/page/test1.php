<?php
/**
 * The first test page
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
 * @version    Subversion: $Id: test1.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * The first test page
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */
final class Test1 extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->setTitle('Test Page 1');
        $this->setBody(DPUNIVERSE_PAGE_PATH . 'test1.html', 'file');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, '/'),
            'Test Page 1');

        // Creates a flower based on the standard DpObject, moves it here:
        $ob = get_current_dpuniverse()->newDpObject(DPUNIVERSE_STD_PATH
            . 'DpObject.php');
        $ob->addId('flower', 'purple flower');
        $ob->setTitle('purple flower');
        $ob->setTitleImg(DPUNIVERSE_IMAGE_URL . 'flower.gif');
        $ob->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
            . 'flower_body.jpg" width="190" height="216" border="0" '
            . 'alt="" align="left" style="margin-right: 15px; border: solid '
            . '1px black" />It is the purple white flower of the Dutchman\'s '
            . 'Pipe.');
        $ob->moveDpObject($this);
    }
}
?>