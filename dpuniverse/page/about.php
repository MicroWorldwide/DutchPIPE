<?php
/**
 * The About DutchPIPE page
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
 * @version    Subversion: $Id: about.php 278 2007-08-19 22:52:25Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * The About DutchPIPE page
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
final class About extends DpPage
{
    private $mrFlowerObj = NULL;

    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dp_text('About DutchPIPE');
        $this->setBody(DPUNIVERSE_PAGE_PATH . 'about.html', 'file');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            dp_text('About DutchPIPE'));
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        if (is_null($this->mrFlowerObj)
                || !$this->isPresent($this->mrFlowerObj)) {
            // Creates a flower based on the standard DpObject, moves it here:
            $this->mrFlowerObj = get_current_dpuniverse()->newDpObject(
                DPUNIVERSE_STD_PATH . 'DpObject.php');
            $this->mrFlowerObj->addId(explode('#',
                dp_text('flower#purple flower')));
            $this->mrFlowerObj->title = dp_text('purple flower');
            $this->mrFlowerObj->titleDefinite = dp_text('the purple flower');
            $this->mrFlowerObj->titleIndefinite = dp_text('a purple flower');
            $this->mrFlowerObj->titleImg = DPUNIVERSE_IMAGE_URL . 'flower.gif';
            $this->mrFlowerObj->body = '<img src="' . DPUNIVERSE_IMAGE_URL
                . 'flower_body.jpg" width="190" height="216" border="0" '
                . 'alt="" align="left" style="margin-right: 15px; border: solid '
                . '1px black" />'
                . dp_text('It is the purple white flower of the Dutchman\'s Pipe.');
            $this->mrFlowerObj->value = 20;

            if (WEIGHT_TYPE_NONE !== WEIGHT_TYPE) {
                $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
                if (WEIGHT_TYPE_ABSTRACT === WEIGHT_TYPE) {
                    $this->weight = 1;
                } elseif (WEIGHT_TYPE_METRIC === WEIGHT_TYPE) {
                    $this->weight = 9; /* Grams */
                } elseif (WEIGHT_TYPE_USA === WEIGHT_TYPE) {
                    $this->weight = 0.3; /* Ounces */
                }
            }
            if (VOLUME_TYPE_NONE !== VOLUME_TYPE) {
                $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
                if (VOLUME_TYPE_ABSTRACT === VOLUME_TYPE) {
                    $this->volume = 1;
                } elseif (VOLUME_TYPE_METRIC === VOLUME_TYPE) {
                    $this->volume = 5;
                } elseif (VOLUME_TYPE_USA === VOLUME_TYPE) {
                    $this->volume = 0.17;
                }
            }

            $this->mrFlowerObj->moveDpObject($this);
        }

        if (!($credits = $this->isPresent(dp_text('credits')))) {
            $credits = get_current_dpuniverse()->newDpObject(DPUNIVERSE_OBJ_PATH
                . 'credits.php');
            $credits->amount = 20;
            $credits->moveDpObject($this);
        }
        elseif ($credits->amount < 20) {
            $credits->amount = 20;
        }
    }
}
?>
