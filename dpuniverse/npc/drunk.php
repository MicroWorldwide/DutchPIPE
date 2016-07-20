<?php
/**
 * 'Drunk' class to create a drunk man carrying a golden key
 *
 * DutchPIPE version 0.2; PHP version 5
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
 * @version    Subversion: $Id: drunk.php 243 2007-07-08 16:26:23Z ls $
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
 * A drunk man carrying a golden key
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
final class Drunk extends DpNpc
{
    /**
     * Array to be filled with chat lines
     */
    private $mChat;

    /**
     * Key object
     */
    private $mrGoldenKeyObj;

    /**
     * Array of livings that gave us non-beer objects
     */
    public $mGiven = array();

    /**
     * Sets up the NPC at object creation time
     */
    public function createDpNpc()
    {
        // Standard setup calls:
        $this->addId(explode('#', dptext('drunk#man')));
        $this->title = dptext('drunk man');
        $this->titleDefinite = dptext('the drunk man');
        $this->titleIndefinite = dptext('a drunk man');
        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'drunk.gif';

        $this->body = '<img src="' . DPUNIVERSE_IMAGE_URL
            . 'drunk_body.gif" width="115" height="200" border="0" alt="" '
            . 'align="left" style="margin-right: 15px" />'
            . dptext('A drunk man. He looks thirsty.<br />');

        // Sets up chat lines:
        $this->mChat = array(
            dptext('The drunk man says: *hIc* hEelLo thERe! *BuUrp*<br />'),
            dptext('The drunk man says: whAaaT I *HiC* NEed iS a dRInK!<br />'),
            dptext('The drunk man says: raaAAaH!<br />'));
    }

    /**
     * Called at regular intervals
     */
    public function resetDpNpc()
    {
        if (is_null($this->mrGoldenKeyObj)
                || !$this->isPresent($this->mrGoldenKeyObj)) {
            // Creates a golden key based on the standard DpObject, moves it
            // here:
            $this->mrGoldenKeyObj = get_current_dpuniverse()->newDpObject(
                DPUNIVERSE_STD_PATH . 'DpObject.php');
            $this->mrGoldenKeyObj->addId(dptext('key'));
            $this->mrGoldenKeyObj->title = dptext('golden key');
            $this->mrGoldenKeyObj->titleDefinite = dptext('the golden key');
            $this->mrGoldenKeyObj->titleIndefinite = dptext('a golden key');
            $this->mrGoldenKeyObj->titleImg = DPUNIVERSE_IMAGE_URL
                . 'golden_key.png';
            $this->mrGoldenKeyObj->body = '<img src="' . DPUNIVERSE_IMAGE_URL
                . 'golden_key.png" width="44" height="100" border="0" '
                . 'alt="" align="left" style="margin-right: 15px" />'
                . dptext('A golden key.<br />You wonder what it can unlock...');
            $this->mrGoldenKeyObj->value = 180;
            $this->mrGoldenKeyObj->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            $this->mrGoldenKeyObj->weight = 1;
            $this->mrGoldenKeyObj->volume = 1;
            $this->mrGoldenKeyObj->isNoStore = new_dp_property(TRUE);
            $this->mrGoldenKeyObj->moveDpObject($this);
        }
    }


    /**
     * Called each 'heartbeat', randomly chat and perform actions
     */
    function timeoutHeartBeat()
    {
        if (3 == mt_rand(1, 7) && FALSE !== ($env = $this->getEnvironment())) {
            $env->tell($this->mChat[mt_rand(0, sizeof($this->mChat) - 1)]);
        }

        DpNpc::timeoutHeartBeat();
    }

   function eventDpNpc($name)
    {
        // Do something when someone drops an empty glass on this page:
        if (EVENT_ENTERED_INV === $name) {
            $ob = func_get_arg(1);
            if ($ob->isId(dptext('glass')) && TRUE === $ob->isFull) {
                $this->setTimeout('timeoutCheckInventory', 4);
            } else {
                $from = func_get_arg(2);
                $this->mGiven[] = array($ob, $from);
                $this->setTimeout('timeoutDrop', 4);
            }
        }
    }

    function timeoutCheckInventory()
    {
        if (FALSE === ($inv = $this->getInventory())) {
            return;
        }
        $inv = $this->getInventory();
        $count = 0;
        foreach ($inv as &$ob) {
            if ($ob->isId(dptext('glass')) && TRUE === $ob->isFull) {
                $this->performAction(dptext('drink beer'));
                if (!$ob->isFull) {
                    $this->performAction(
                        dptext('say thAT\'S muCH *Hic* BEtTer!'));
                    $this->setTimeout('timeoutDropKey', 4);
                }
            }
        }
    }

    function timeoutDropKey()
    {
        if ($this->isPresent(dptext('golden key'))) {
            $this->performAction(
                dptext('say pErhAPs yOU hAvE a *BuUURp* USe foR thIS?'));
            $this->performAction(dptext('drop golden key'));
        }
    }

    function timeoutDrop()
    {
        foreach ($this->mGiven as $item_data) {
            $ob = $item_data[0];
            $from = $item_data[1];

            if ($this->getEnvironment()->isPresent($from)
                    && $this->isPresent($ob)) {
                $this->performAction(
                    dptext('say tHanX *bUUrp* bUu-UUUt I doN\'T NEed tHaT! *hIC*'));
                $this->performAction(sprintf(dptext("give %s to %s"),
                    $ob->getTitle(DPUNIVERSE_TITLE_TYPE_NAME),
                    $from->getTitle(DPUNIVERSE_TITLE_TYPE_NAME)));
           }
        }
        $this->mGiven = array();
    }
}
?>
