<?php
/**
 * 'Barkeeper' class to create a barkeeper serving free beer
 *
 * DutchPIPE version 0.1; PHP version 5
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
 * @version    Subversion: $Id: barkeeper.php 185 2007-06-09 21:53:43Z ls $
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
 * A barkeeper serving free beer
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_npc
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.0
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpNpc
 */
final class Barkeeper extends DpNpc
{
    /**
     * Array to be filled with chat lines
     */
    private $mChat;

    /**
     * Has it resetted before? TRUE if it hasn't.
     */
    private $mFirstReset = TRUE;

    /**
     * Sets up the NPC at object creation time
     */
    public function createDpNpc()
    {
        // Standard setup calls:
        $this->addId(explode('#', dptext('barkeeper')));
        $this->title = dptext('barkeeper');
        $this->titleDefinite = dptext('the barkeeper');
        $this->titleIndefinite = dptext('a barkeeper');
        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'barkeeper.gif';
        $this->body = '<img src="' . DPUNIVERSE_IMAGE_URL
            . 'barkeeper_body.gif" width="125" height="200" border="0" alt="" '
            . 'align="left" style="margin-right: 15px" />'
            . dptext('The barkeeper is serving free beer!<br />');

        // Sets up chat lines:
        $this->mChat = array(
            dptext('The barkeeper says: Today free beer for everybody!<br />'),
            dptext('The barkeeper hiccups.<br />'),
            dptext('The barkeeper drinks a cool, fresh beer.<br />'),
            dptext('The barkeeper says: Hello there.<br />'),
            dptext('The barkeeper stumbles, seems to fall, but takes a step and recovers.<br />'),
            dptext('The barkeeper says: Why don\'t you have a beer with me?<br />'));

        // Fetch beer in a few seconds:
        $this->setTimeout('timeoutMakeNewBeer', 4);
    }

    /**
     * Makes new beer
     */
    function resetDpNpc()
    {
        if ($this->mFirstReset) {
            $this->mFirstReset = FALSE;
            return;
        }
        $this->actionShout(dptext('shout'),
            dptext('Today free beer in the bar for everyone!'));
        $this->timeoutMakeNewBeer();
    }

    /**
     * Checks for dropped empty glasses using EVENT_ENTERED_ENV event
     *
     * If an empty glass was dropped in the environment of the barkeeper,
     * calls {@link timeoutCheckEmptyGlasses()} in 4 seconds.
     *
     * @param   boolean $name       name of event
     * @see     timeoutCheckEmptyGlasses()
     */
    function eventDpNpc($name)
    {
        // Do something when someone drops an empty glass on this page:
        if (EVENT_ENTERED_ENV === $name) {
            $ob = func_get_arg(1);
            if ($ob->isId(dptext('glass'))
                    && FALSE === $ob->isFull) {
                $this->setTimeout('timeoutCheckEmptyGlasses', 4);
            }
        }
    }

    /**
     * Removes empty glasses from the environment of this barkeeper
     *
     * Makes the barkeeper carry away empty glasses, calls
     * {@link timeoutMakeNewBeer()} in 4 seconds.
     *
     * @see     event(), timeoutMakeNewBeer()
     */
    function timeoutCheckEmptyGlasses()
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return;
        }
        $inv = $env->getInventory();
        $count = 0;
        foreach ($inv as &$ob) {
            if ($ob->isId(dptext('glass')) && FALSE === $ob->isFull) {
                $ob->removeDpObject();
                $count++;
            }
        }

        if ($count > 0) {
            if ($count < 2) {
                $env->tell(dptext('The barkeeper notices the empty beer glass and carries it away.<br />'));
            } else {
                $env->tell(dptext('The barkeeper notices the empty beer glasses and carries them away.<br />'));
            }

            $this->setTimeout('timeoutMakeNewBeer', 4);
        }
    }

    /**
     * Makes the barkeeper serve new beer if there aren't enough around
     *
     * Makes sure there are at least 4 beers in the barkeeper's environment.
     *
     * @see     timeoutCheckEmptyGlasses()
     */
    function timeoutMakeNewBeer()
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return;
        }
        $inv = $env->getInventory();
        $nr_of_beers = 0;
        foreach ($inv as &$ob) {
            if ($ob->isId(dptext('glass')) && FALSE !== $ob->isFull) {
                $nr_of_beers++;
            }
        }
        $nr_of_beers = 4 - $nr_of_beers;
        if ($nr_of_beers > 0) {
            $served_beers = $nr_of_beers;
            while ($nr_of_beers > 0) {
                $beer_obj = get_current_dpuniverse()->newDpObject(
                    DPUNIVERSE_STD_PATH . 'DpDrink.php');

                $beer_obj->addId(explode('#',
                    dptext('beer#cool beer#fresh beer#cool fresh beer#cool,fresh beer#cool, fresh beer#glass')));
                $beer_obj->setTitle(dptext('cool, fresh beer'));
                $beer_obj->setTitleDefinite(dptext('the cool, fresh beer'));
                $beer_obj->setTitleIndefinite(dptext('a cool, fresh beer'));
                $beer_obj->setTitleImg(DPUNIVERSE_IMAGE_URL . 'beer_full.gif');
                $beer_obj->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
                    . 'beer_full_body.gif" alt="" border="0" align="left" style="margin-'
                    . 'right: 20px"/>' . dptext('A cool, fresh beer.<br />'));

                $beer_obj->setEmptyIds(explode('#',
                    dptext('glass#beer glass#empty glass#empty beer glass')));
                $beer_obj->setEmptyTitle(dptext('empty beer glass'));
                $beer_obj->setEmptyTitleDefinite(dptext('the empty beer glass'));
                $beer_obj->setEmptyTitleIndefinite(dptext('an empty beer glass'));
                $beer_obj->setEmptyTitleImg(DPUNIVERSE_IMAGE_URL . 'beer_empty.gif');
                $beer_obj->setEmptyBody('<img src="' . DPUNIVERSE_IMAGE_URL
                    . 'beer_empty_body.gif" alt="" border="0" align="left" '
                    . 'style="margin-right: 20px"/>'
                    . dptext('An empty beer glass.<br />'));

                $beer_obj->moveDpObject($env);
                $nr_of_beers--;
            }

            if ($served_beers == 1) {
                $env->tell(dptext('The barkeeper serves a new beer. Be quick and get your free beer before it\'s gone!<br />'));
            } else {
                $env->tell(dptext('The barkeeper serves some new beers. Be quick and get your free beer before it\'s gone!<br />'));
            }
        }
    }

    /**
     * Called each 'heartbeat', randomly chat and perform actions
     */
    function timeoutHeartBeat()
    {
        if (3 == mt_rand(1, 7) && FALSE !== ($env = $this->getEnvironment())) {
            $env->tell($this->mChat[mt_rand(0, sizeof($this->mChat) - 1)]);
            if (2 == mt_rand(1, 3)) {
                $this->actionTake(dptext('get'), dptext('beer'));
            } elseif (50 == mt_rand(50, 51)) {
                $this->actionDrop(dptext('drop'), dptext('all'));
            }
        }
        DpNpc::timeoutHeartBeat();
    }
}
?>
