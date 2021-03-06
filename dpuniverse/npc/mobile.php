<?php
/**
 * 'Mobile' class to create a mobile computer generated character
 *
 * DutchPIPE version 0.4; PHP version 5
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
 * @version    Subversion: $Id: mobile.php 308 2007-09-02 19:18:58Z ls $
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
 * @version    Release: 0.2.1
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
        $this->addId = explode('#', dp_text('mobile#npc#mobile npc'));
        $this->title = dp_text('mobile NPC');
        $this->titleDefinite = dp_text('the mobile NPC');
        $this->titleIndefinite = dp_text('a mobile NPC');
        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'npc.gif';
        $this->titleImgWidth = 66;
        $this->titleImgHeight = 100;

        $this->body =
            dp_text('A mobile computer generated character that runs around the website.<br />');
        $this->addAction(dp_text('kick'), dp_text('kick'), 'actionKick',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ENVIRONMENT);
        $this->addAction(array(dp_text('admin'), dp_text('silence')), 'silence',
            'actionSilence', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_ENVIRONMENT);
        $this->isSilent = new_dp_property(FALSE);
        $this->isNoCleanUp = new_dp_property(TRUE);

        // Sets up chat lines:
        $this->mChat = array(
            dp_text("The mobile NPC says: This is sooo depressing.<br />"),
            dp_text("The mobile NPC says: How boring.<br />"),
            dp_text("The mobile NPC says: Oh this is so exciting.<br />"));
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
                            $this->isPresent(dp_text('glass')) && $max--; ) {
                        $this->performAction(dp_text('drop glass'));
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
                        && FALSE == $this->isPresent(dp_text('glass 4'))) {
                    if ($env->isPresent(dp_text('glass'))) {
                        $this->performAction(
                            dp_text('say What a mess, and who has to clean it up? Yes...'));
                        $this->performAction(dp_text('get glass'));
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
        } while ($rnd_link === dp_text('login'));
        if ($rnd_link[0] === DPUNIVERSE_NAVLOGO) {
            $linkcommand = dp_text('home');
        } else {
            $linkcommand = explode(' ', $rnd_link);
            $linkcommand = dp_strtolower($rnd_link);
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

        if (!dp_strlen($noun)) {
            $living->setActionFailure(dp_text('Kick who?<br />'));
            return FALSE;
        }

        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (!$this->isId($noun)) {
            $living->setActionFailure(ucfirst(sprintf(
                dp_text('%s is not here.<br />'), $noun)));
            return FALSE;
        }

        $living->tell(sprintf(dp_text('You give %s a good hard kick!<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(sprintf(dp_text('%s gives %s a good hard kick!<br />'),
            $living->getTitle(),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)), $living);
        $this->tell($living->getTitle(sprintf(dp_text(
            '%s gives you a good hard kick!<br/>'),
            DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell('<window autoclose="2500" styleclass="dpwindow_drink">'
            . '<div style="text-align: center"><h1>' . dp_text('*KICK*')
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
        $this->performAction(
            dp_text("say Oh, ok, I know when I'm not wanted."));
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

        if (!dp_strlen($noun)) {
            $living->setActionFailure(dp_text('Silence who?<br />'));
            return FALSE;
        }

        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (!$this->isId($noun)) {
            $living->setActionFailure(sprintf(dp_text('%s is not here.<br />'),
                ucfirst($noun)));
            return FALSE;
        }

        $this->isSilent = !$this->isSilent;

        if ($this->isSilent) {
            $living->tell(sprintf(dp_text('You silence %s.<br />'),
                $this->getTitle()));
            $env->tell(sprintf(dp_text('%s silences %s.<br />'),
                $living->getTitle(), $this->getTitle()), $living);
            $this->tell(sprintf(dp_text('%s silences you.<br/>'),
                $living->getTitle()));
        } else {
            $living->tell(sprintf(dp_text('You allow %s to talk again.<br />'),
                $this->getTitle()));
            $env->tell(sprintf(dp_text('%s allows %s to talk again.<br />'),
                $living->getTitle(), $this->getTitle()), $living);
            $this->tell(sprintf(dp_text('%s allows you to talk again.<br/>'),
                $living->getTitle()));
        }

        return TRUE;
    }
}
?>
