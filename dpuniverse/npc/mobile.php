<?php
/**
 * A mobile computer generated character
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
 * @version    Subversion: $Id: mobile.php 22 2006-05-30 20:40:55Z ls $
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
 * A mobile computer generated character
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpNpc
 */
final class Mobile extends DpNpc
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
        $this->addId(explode('#', dptext('mobile#npc#mobile npc')));
        $this->setTitle(dptext('mobile NPC'));
        $this->setTitleDefinite(dptext('the mobile NPC'));
        $this->setTitleIndefinite(dptext('a mobile NPC'));
        $this->setTitleImg(DPUNIVERSE_IMAGE_URL . 'npc.gif');
        $this->setBody(dptext('A mobile computer generated character that runs around the website.<br />'));
        $this->addAction(dptext('kick'), dptext('kick'), 'actionKick',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ENVIRONMENT);

        // Sets up chat lines:
        $this->mChat = array(
            dptext("The mobile NPC says: This is sooo depressing.<br />"),
            dptext("The mobile NPC says: How boring.<br />"),
            dptext("The mobile NPC says: Oh this is so exciting.<br />"));
    }

    /**
     * Called each 'heartbeat', randomly chat and wander
     */
    function timeoutHeartBeat()
    {
        // :KLUDGE: mt_rand behaves badly with small nummers on some systems, 30
        // is added/reduced everywhere
        if (FALSE !== ($env = $this->getEnvironment())) {
            if (35 == mt_rand(30, 40)) {
                $env->tell($this->mChat[mt_rand(0, sizeof($this->mChat) - 1)]);
            }
            elseif (38 == mt_rand(30,51)) {
                $this->randomWalk();
            }
        }
        DpNpc::timeoutHeartBeat();
    }

    /**
     * Leave through a random link
     */
     function randomWalk()
     {
        if (FALSE === ($env = $this->getEnvironment())
                || !method_exists($env, 'getExits')
                || !($exits = $env->getExits())
                || !($exits_cnt = count($exits))) {
            return;
        }
        $keys = array_keys($exits);
        $i = 0;
        do {
            $rnd = mt_rand(30, 30 + ($exits_cnt - 1)) - 30;
            $rnd_link = $keys[mt_rand(0, $exits_cnt - 1)];
            if ($i++ > 9) {
                return;
            }
        } while ($rnd_link === dptext('login'));
        if ($rnd_link[0] === DPUNIVERSE_NAVLOGO) {
            $linkcommand = dptext('home');
        } else {
            $linkcommand = explode(' ', $rnd_link);
            $linkcommand = strtolower($rnd_link);
        }
        $this->performAction($linkcommand);
    }

    function actionKick($verb, $noun)
    {
        $living = get_current_dpobject();

        if (!strlen($noun)) {
            $living->setActionFailure(dptext('Kick who?<br />'));
            return FALSE;
        }

        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (!$this->isId($noun)) {
            $living->setActionFailure(ucfirst(sprintf(
                dptext('%s is not here.<br />'), $noun)));
            return FALSE;
        }

        $living->tell(sprintf(dptext('You give %s a good hard kick!<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(sprintf(dptext('%s gives %s a good hard kick!<br />'),
            $living->getTitle(),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)), $living);
        $this->tell($living->getTitle(sprintf(dptext(
            '%s gives you a good hard kick!<br/>'),
            DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell('<window autoclose="2500" styleclass="dpwindow_drink">'
            . '<div style="text-align: center"><h1>' . dptext('*KICK*')
            . '</h1></div></window>');

        // Fetch beer in a few seconds:
        $this->setTimeout('timeoutKick', 3);

        return TRUE;
    }

    function timeoutKick()
    {
        $this->performAction(gettext(
            "say Oh, ok, I know when I'm not wanted."));
        $this->randomWalk();
    }
}
?>
