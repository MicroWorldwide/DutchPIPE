<?php
/**
 * 'Mobile' class to create a mobile computer generated character
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
 * @version    Subversion: $Id: mobile.php 185 2007-06-09 21:53:43Z ls $
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
 * Creates the following DutchPIPE properties:<br />
 *
 * - boolean <b>isSilent</b> - Set to FALSE
 * - boolean <b>isNoCleanUp</b> - Set to TRUE
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
        $this->addId = explode('#', dptext('mobile#npc#mobile npc'));
        $this->title = dptext('mobile NPC');
        $this->titleDefinite = dptext('the mobile NPC');
        $this->titleIndefinite = dptext('a mobile NPC');
        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'npc.gif';
        $this->body =
            dptext('A mobile computer generated character that runs around the website.<br />');
        $this->addAction(dptext('kick'), dptext('kick'), 'actionKick',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ENVIRONMENT);
        $this->addAction('silence!', 'silence!', 'actionSilence',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_ENVIRONMENT);
        $this->isSilent = new_dp_property(FALSE);
        $this->isNoCleanUp = new_dp_property(TRUE);

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
        if (!$this->isSilent) {
            // :KLUDGE: mt_rand behaves badly with small nummers on some
            // systems, 30 is added/reduced everywhere
            if (FALSE !== ($env = $this->getEnvironment())) {
                $env_is_bar = $env->location === (DPUNIVERSE_PAGE_PATH
                    . 'bar.php');

                if ($env_is_bar) {
                    for ($max = 10;
                            $this->isPresent(dptext('glass')) && $max--; ) {
                        $this->performAction(dptext('drop glass'));
                    }
                }

                if (35 === mt_rand(30, 40)) {
                    $env->tell($this->mChat[mt_rand(0,
                        sizeof($this->mChat) - 1)]);
                }
                elseif (38 === mt_rand(30,51)) {
                    $this->randomWalk();
                }
                elseif ((33 === mt_rand(30, 40)) && FALSE === $env_is_bar
                        && FALSE == $this->isPresent(dptext('glass 4'))) {
                    if ($env->isPresent(dptext('glass'))) {
                        $this->performAction(
                            dptext('say What a mess, and who has to clean it up? Yes...'));
                        $this->performAction(dptext('get glass'));
                    }
                }
            }
        }
        DpNpc::timeoutHeartBeat();
    }

    /**
     * Leaves through a random link
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

    /**
     * Gives the mobile a good hard kick!
     *
     * @param   string  $verb       the action, "kick"
     * @param   string  $noun       who to kick, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
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
        $this->setTimeout('timeoutKicked', 3);

        return TRUE;
    }

    /**
     * Makes the mobile complain and take a random exit
     */
    function timeoutKicked()
    {
        $this->performAction(dptext("say Oh, ok, I know when I'm not wanted."));
        $this->randomWalk();
    }

    /**
     * Toggles between silent/talking&moving mode, admininistators only
     *
     * @param   string  $verb       the action, "silence!"
     * @param   string  $noun       who to silence, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionSilence($verb, $noun)
    {
        $living = get_current_dpobject();

        if (!strlen($noun)) {
            $living->setActionFailure(dptext('Silence who?<br />'));
            return FALSE;
        }

        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (!$this->isId($noun)) {
            $living->setActionFailure(sprintf(dptext('%s is not here.<br />'),
                ucfirst($noun)));
            return FALSE;
        }

        $this->isSilent = !$this->isSilent;

        if ($this->isSilent) {
            $living->tell(sprintf(dptext('You silence %s.<br />'),
                $this->getTitle()));
            $env->tell(sprintf(dptext('%s silences %s.<br />'),
                $living->getTitle(), $this->getTitle()), $living);
            $this->tell(sprintf(dptext('%s silences you.<br/>'),
                $living->getTitle()));
        } else {
            $living->tell(sprintf(dptext('You allow %s to talk again.<br />'),
                $this->getTitle()));
            $env->tell(sprintf(dptext('%s allows %s to talk again.<br />'),
                $living->getTitle(), $this->getTitle()), $living);
            $this->tell(sprintf(dptext('%s allows you to talk again.<br/>'),
                $living->getTitle()));
        }

        return TRUE;
    }
}
?>
