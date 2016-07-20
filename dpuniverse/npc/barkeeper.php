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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: barkeeper.php 2 2006-05-16 00:20:42Z ls $
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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
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
     * Sets up the NPC at object creation time
     */
    public function createDpNpc()
    {
        // Standard setup calls:
        $this->addId('barkeeper');
        $this->setTitle('barkeeper');
        $this->setTitleImg(DPUNIVERSE_IMAGE_URL . 'barkeeper.gif');
        $this->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
            . 'barkeeper_body.gif" width="125" height="200" border="0" alt="" '
            . 'align="left" style="margin-right: 15px" />The barkeeper is '
            . 'serving free beer!<br />');

        // Sets up chat lines:
        $this->mChat = array(
            'The barkeeper says: Today free beer for everybody!<br />',
            'The barkeeper hiccups.<br />',
            'The barkeeper drinks a cool, fresh beer.<br />',
            'The barkeeper says: Hello there.<br />',
            'The barkeeper stumbles, seems to fall, but takes a step and '
                . 'recovers.<br />',
            'The barkeeper says: Why don\'t you have a beer with me?<br />');

        // Fetch beer in a few seconds:
        $this->setTimeout('timeoutMakeNewBeer', 4);
    }

    function resetDpNpc()
    {
        $this->actionShout('shout', 'Today free beer in the bar for everyone!');
        $this->timeoutMakeNewBeer();
    }

    function event($name)
    {
        // Do something when someone drops an empty glass on this page:
        if (EVENT_ENTERED_ENV === $name) {
            $ob = func_get_arg(1);
            if ($ob->isId('glass')
                    && FALSE === $ob->getProperty('is_full')) {
                $this->setTimeout('timeoutCheckEmptyGlasses', 4);
            }
        }
    }

    function timeoutCheckEmptyGlasses()
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return;
        }
        $inv = $env->getInventory();
        $count = 0;
        foreach ($inv as &$ob) {
            if ($ob->isId('glass')
                    && FALSE === $ob->getProperty('is_full')) {
                $ob->removeDpObject();
                $count++;
            }
        }

        if ($count > 0) {
            $env->tell('The barkeeper notices the empty beer glass'
                . ($count < 2 ? ' and carries it away'
                : 'es and carries them away') . '<br />');
            $this->setTimeout('timeoutMakeNewBeer', 4);
        }
    }

    function timeoutMakeNewBeer()
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return;
        }
        $inv = $env->getInventory();
        $nr_of_beers = 0;
        foreach ($inv as &$ob) {
            if ($ob->isId('glass')
                    && FALSE !== $ob->getProperty('is_full')) {
                $nr_of_beers++;
            }
        }
        $nr_of_beers = 4 - $nr_of_beers;
        if ($nr_of_beers > 0) {
            $served_beers = $nr_of_beers;
            while ($nr_of_beers > 0) {
                $beer_obj = get_current_dpuniverse()->newDpObject(
                    DPUNIVERSE_STD_PATH . 'DpDrink.php');

                $beer_obj->addId('beer', 'cool beer', 'fresh beer',
                    'cool fresh beer', 'cool,fesh beer', 'cool, fresh beer',
                    'glass');
                $beer_obj->setTitle('cool, fresh beer');
                $beer_obj->setTitleImg(DPUNIVERSE_IMAGE_URL . 'beer_full.gif');
                $beer_obj->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
                    . 'beer_full_body.gif" alt="" border="0" align="left" style="margin-'
                    . 'right: 20px"/>A cool, fresh beer.<br />');

                $beer_obj->setEmptyIds(array('glass', 'beer glass',
                    'empty glass', 'empty beer glass'));
                $beer_obj->setEmptyTitle('empty beer glass');
                $beer_obj->setEmptyTitleImg(DPUNIVERSE_IMAGE_URL . 'beer_empty.gif');
                $beer_obj->setEmptyBody('<img src="' . DPUNIVERSE_IMAGE_URL
                    . 'beer_empty_body.gif" alt="" border="0" align="left" style="margin-'
                    . 'right: 20px"/>An empty beer glass.<br />');

                $beer_obj->moveDpObject($env);
                $nr_of_beers--;
            }
            $env->tell('The barkeeper serves ' . ($served_beers == 1
                ? 'a new beer' : 'some new beers')
                . '. Be quick and get your free beer before it\'s gone!<br />');
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
                $this->actionTake('get', 'beer');
            } elseif (50 == mt_rand(50, 51)) {
                $this->actionDrop('drop', 'all');
            }
        }
        DpNpc::timeoutHeartBeat();
    }
}
?>
