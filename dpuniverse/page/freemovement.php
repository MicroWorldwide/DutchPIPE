<?php
/**
 * The Free Movement Demo page
 *
 * DutchPIPE version 0.3; PHP version 5
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
 * @version    Subversion: $Id: freemovement.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * The Free Movement Demo page
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
final class Freemovement extends DpPage
{
    private $mrFlowerObj = NULL;

    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // This page has it's own template, but this is not necessary for
        // draggable objects:
        $this->template = DPSERVER_TEMPLATE_PATH . 'freemovement.tpl';

        // Set moving area to 1 or 2 for draggable objects.
        // 1: Only draggable within inventory area; 2: Dragganle on entire page.
        // This is all you have to do:
        $this->isMovingArea = 2;

        // Standard setup calls:
        $this->title = 'Free Movement Demo';
        $this->body =
            dptext('This is an experiment with draggable objects.<br />
Try it for yourself!<br />
Others on the page see your movements.<br />');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dptext('Showcases'), DPUNIVERSE_PAGE_PATH . 'showcases.php'),
            dptext('Free Movement Demo'));

        // Alternative verbs to go to another page, also used by wandering NPCs:
        $this->addExit('shop', DPUNIVERSE_PAGE_PATH . 'shop.php');
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
                dptext('flower#purple flower')));
            $this->mrFlowerObj->title = dptext('purple flower');
            $this->mrFlowerObj->titleDefinite = dptext('the purple flower');
            $this->mrFlowerObj->titleIndefinite = dptext('a purple flower');
            $this->mrFlowerObj->titleImg = DPUNIVERSE_IMAGE_URL . 'flower.gif';
            $this->mrFlowerObj->body = '<img src="' . DPUNIVERSE_IMAGE_URL
                . 'flower_body.jpg" width="190" height="216" border="0" '
                . 'alt="" align="left" style="margin-right: 15px; border: solid '
                . '1px black" />'
                . dptext('It is the purple white flower of the Dutchman\'s Pipe.');
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

        if (!($credits = $this->isPresent(dptext('credits')))) {
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
