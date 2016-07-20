<?php
/**
 * An object which is "alive", common code shared between users and NPCs
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
 * @version    Subversion: $Id: DpLiving.php 20 2006-05-19 02:22:39Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */

/**
 * Builts upon the standard DpObject class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpObject.php');

/**
 * An object which is "alive", common code shared between users and NPCs
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */
class DpLiving extends DpObject
{
    /**
     * @var         string    Message shown when an action fails, can be set by actions
     * @access      private
     */
    private $mActionFailure = DPUNIVERSE_ACTION_DEFAULT_FAILURE;

    /**
     * @var         string    Default message shown when an action fails
     * @access      private
     */
    private $mActionDefaultFailure = DPUNIVERSE_ACTION_DEFAULT_FAILURE;

    /**
     * Creates this living
     *
     * Adds standard actions which can be performed by this object, usually a
     * user or a computer controlled character.
     *
     * Calls createDpLiving in the inheriting class (if defined)
     *
     * Starts a "heartbeat", see timeoutHeartBeat.
     */
    function createDpObject()
    {
        $this->addProperty('is_living');
        $this->setBody('This description hasn\'t been set yet.<br />');

        /* Actions for everybody */
        $this->addAction('inventory', array('inventory', 'inv', 'i'), 'actionInventory', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('examine', array('examine', 'exam', 'exa', 'x', 'la'), 'actionExamine', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF | DP_ACTION_TARGET_LIVING | DP_ACTION_TARGET_OBJINV | DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction("who's here?", 'who', 'actionWho', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('take', array('take', 'get'), 'actionTake', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('drop', 'drop', 'actionDrop', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_OBJINV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('say', 'say', 'actionSay', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('help', 'help', 'actionHelp', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('give...', 'give', 'actionGive', array($this, 'actionGiveOperant'), DP_ACTION_TARGET_OBJINV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('tell', 'tell', 'actionTell', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('shout', 'shout', 'actionShout', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('smile', 'smile', 'actionEmotion', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('grin', 'grin', 'actionEmotion', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('laugh', 'laugh', 'actionEmotion', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('shrug', 'shrug', 'actionEmotion', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('pat', 'pat', 'actionEmotion', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('high five', 'high5', 'actionEmotion', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('hug', 'hug', 'actionEmotion', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('kiss', 'kiss', 'actionEmotion', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('dance', 'dance', 'actionEmotion', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('emote', 'emote', 'actionEmotion', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('settings', array('settings', 'config'), 'actionSettings', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('look', array('look', 'l', 'see'), 'actionLook', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('source', 'source', 'actionSource', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction('page links', 'links', 'actionLinks', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        /* Actions for admin only */
        $this->addAction('svars', 'svars', 'actionSvars', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction('force', 'force', 'actionForce', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);

        if (method_exists($this, 'createDpLiving')) {
            $this->createDpLiving();
        }

        $this->setTimeout('timeoutHeartBeat', 2);
    }

    /**
     * Resets this living
     *
     * Periodically calls resetDpLiving in the inheriting class (if defined)
     */
    function resetDpObject()
    {
        if (method_exists($this, 'resetDpLiving')) {
            $this->resetDpLiving();
        }
    }

    /**
     * Calls itself every 'heartbeat'. Mask this method to make timed stuff
     * happen.
     */
    function timeoutHeartBeat()
    {
        $this->setTimeout('timeoutHeartBeat', 2);
    }

    /**
     * Gets the available number of avatars images
     *
     * Searches the DPUNIVERSE_AVATAR_PATH directory for files ending with
     * "_body.gif".
     */
    static function _getNrOfAvatars()
    {
        $entries = 0;
        $d = dir(DPUNIVERSE_AVATAR_PATH);
        while (false !== ($entry = $d->read())) {
            if ($entry !== '.' && $entry !== '..' && strlen($entry) > 13
                    && substr($entry, -9) == '_body.gif') {
                $entries++;
            }
        }
        return $entries;
    }

    /**
     * Sets the message shown to the user when an action fails
     *
     * When the user performs an action but the action fails, there are two
     * ways for the method implementing the action to communicate the failure
     * to the user.
     *
     * 1. Call tell in the user and return TRUE:
     *
     *        $user->tell('Action foo failed because of bar.');
     *        return TRUE;
     *
     *    The action system will stop looking for other ways to perform the
     *    action.
     *
     * 2. Call setActionFailure in the user and return FALSE. This failure setup
     *    is used when different objects can have the same actions implemented
     *    with addAction - if one fails, another might still succeed.
     *
     *        $user->setActionFailure('Action foo failed because of bar.');
     *        return FALSE;
     *
     *    The action system will continue looking for other ways to perform the
     *    action. If it doesn't find any, the previously set failure message is
     *    communicated to the user. Otherwise, the next method implementing the
     *    action takes over. The action system will continue looking for
     *    object/methods implementing the action, until TRUE is returned or no
     *    more implementations are found. In that case, the last set action
     *    failure is returned. If no action failure was set, the default failure
     *    message is shown (see setActionDefaultFailure).
     */
    final public function setActionFailure($actionFailure)
    {
        $this->mActionFailure = $actionFailure;
    }

    /**
     * Gets the last set message set by a failing user action
     */
    final public function getActionFailure()
    {
        return $this->mActionFailure;
    }

    /**
     * Sets the default message shown to the user when an action fails, for
     * example "What?".
     *
     * Used when no message was set with setActionFailure
     */
    final public function setActionDefaultFailure($actionDefaultFailure)
    {
        $this->mActionDefaultFailure = $actionDefaultFailure;
    }

    /**
     * Gets the default message shown to the user when an action fails, for
     * example "What?".
     *
     * Used when no message was set with setActionFailure
     */
    final public function getActionDefaultFailure()
    {
        return $this->mActionDefaultFailure;
    }

    /**
     * Tries to perform the action given by the living
     */
    final public function performAction($action)
    {
        global $grCurrentDpObject;

        $action = trim($action);
        $grCurrentDpObject = $this;

        echo 'Action by ' . $this->getTitle() . ": $action\n";

        $rval = (bool)$this->performActionSubject($action, $this);
        if (TRUE !== $rval) {
            if (strlen($action) && $action !== 'look' && $action !== 'l'
                    && $action !== 'see') {
                $this->tell($this->getActionFailure());
                $this->setActionFailure($this->getActionDefaultFailure());

                if ($this->getProperty('is_user')
                        && $this === get_current_dpuser()) {
                    get_current_dpuniverse()->mrCurrentDpUserRequest->mToldSomething
                        = TRUE;
                }
            }
        }
        $grCurrentDpObject = FALSE;
        return $rval;
    }

    /**
     * Examines an object
     */
    function actionExamine($verb, $noun)
    {
        if (!strlen($noun)) {
            $this->setActionFailure('Examine what?<br />');
            return FALSE;
        }

        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (FALSE === ($ob = $this->isPresent($noun))) {
            if (FALSE === ($ob = $env->isPresent($noun))) {
                $this->setActionFailure("There is no $noun here.<br />");
                return FALSE;
            }
        }

        $this->tell('<window>' . $ob->getAppearance(0, TRUE, NULL,
            $this->getProperty('display_mode'), FALSE, 'dpobinv')
            . '</window>');
        return TRUE;
    }

    function actionLook($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        if (!strlen($noun) || $noun == $this->getUniqueId()) {
            $this->tell('<location>' . $env->getProperty('location')
                . '</location>');
            return TRUE;
        }
        if (strlen($noun) < 4 || substr($noun, 0, 3) !== 'at ') {
            return FALSE;
        }
        $noun = substr($noun, 3);

        if (FALSE === ($ob = $this->isPresent($noun))) {
            if (FALSE === ($ob = $env->isPresent($noun))) {
                $this->setActionFailure("There is no $noun here.<br />");
                return FALSE;
            }
        }
        $this->tell('<window>' . $ob->getAppearance(0, TRUE, NULL,
            $this->getProperty('display_mode'), FALSE) . '</window>');
        return TRUE;
    }

    function actionTake($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (!strlen($noun)) {
            $this->setActionFailure(ucfirst($verb) . ' what?<br />');
            return FALSE;
        }

        if ($noun == 'all') {
            $inv = $env->getInventory();
            $picked_up = FALSE;
            foreach ($inv as &$ob) {
                if (FALSE === $ob->getProperty('is_living')) {
                    $ob->moveDpObject($this);
                    $picked_up = TRUE;
                    $this->tell('You take '
                        . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
                        . '.<br />');
                    $env->tell(ucfirst($this->getTitle(
                        DPUNIVERSE_TITLE_TYPE_DEFINITE)) . ' takes '
                        . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)
                        . '.<br />', $this);
                }
            }
            if (FALSE === $picked_up) {
               $this->tell('There is nothing to pick up here.<br />');
            }
            return TRUE;
        }
        if (FALSE === ($ob = $env->isPresent($noun))) {
            $this->setActionFailure("There is no $noun here.<br />");
            return FALSE;
        }
        if (FALSE !== $ob->getProperty('is_living')) {
            $this->tell($ob->getTitle() . ' refuses to be taken.<br />');
            return TRUE;
        }
        $ob->moveDpObject($this);
        $this->tell('You take ' . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
            . '.<br />');
        $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . ' takes ' . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)
            . '.<br />', $this);
        return TRUE;
    }

    /**
     * Drops an object in the user's environment
     */
    function actionDrop($verb, $noun, $silently = FALSE)
    {
        if (!strlen($noun)) {
            if (FALSE === $silently) {
                $this->setActionFailure('Drop what?<br />');
            }
            return FALSE;
        }
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        if ($noun == 'all') {
            $inv = $this->getInventory();
            if (sizeof($inv) == 0) {
               if (FALSE === $silently) {
                   $this->tell('You have nothing to drop.<br />');
               }
               return TRUE;
            }
            foreach ($inv as &$ob) {
                $ob->moveDpObject($env);
                if (FALSE === $silently) {
                    $this->tell('You drop '
                        . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
                        . '.<br />');
                }
                $env->tell(ucfirst($this->getTitle(
                    DPUNIVERSE_TITLE_TYPE_DEFINITE)) . ' drops '
                    . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)
                    . '.<br />', $this);
            }
            return TRUE;
        }
        if (FALSE === ($ob = $this->isPresent($noun))) {
            if (FALSE === $silently) {
                $this->setActionFailure("There is no $noun here.<br />");
            }
            return FALSE;
        }

        $ob->moveDpObject($env);
        if (FALSE === $silently) {
            $this->tell('You drop '
                . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . '.<br />');
        }
        $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . ' drops ' . $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)
            . '.<br />', $this);
        return TRUE;
    }

    function actionInventory($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        $inventory = $this->getAppearance(0, TRUE, NULL,
            $this->getProperty('display_mode'), -1, 'dpobinv');
        /* :KLUDGE: */
        $inventory = str_replace('You are carrying:',
            '<b>You are carrying:</b>', $inventory);
        $this->tell("<window>$inventory</window>");
        return TRUE;
    }

    function actionSay($verb, $noun)
    {
        if (empty($noun)) {
            $this->tell('Say what?<br />');
            return TRUE;
        }
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        $env->tell(ucfirst($this->getTitle()) . " says: $noun<br />", $this);
        $this->tell("You say: $noun<br />");
        return TRUE;
    }

    function actionGiveOperant($menuon)
    {
        $title = strtolower($menuon->getTitle());
        if (strlen($title) > 4 && substr($title, 0, 4) == 'the ') {
            $title = substr($title, 4);
        } elseif (strlen($title) > 3 && substr($title, 0, 3) == 'an ') {
            $title = substr($title, 3);
        } elseif (strlen($title) > 2 && substr($title, 0, 2) == 'a ') {
            $title = substr($title, 2);
        }
        return "$title to ";
    }

    function actionGive($verb, $noun)
    {
        if (empty($noun)) {
            return FALSE;
        }

        if (FALSE === ($pos = strpos($noun, ' to '))
                || $pos > strlen($noun) - 4) {
            $this->Tell('Give what to who?<br />');
            return TRUE;
        }
        $what = substr($noun, 0, $pos);
        $who = substr($noun, $pos + 4);
        if (!($what_ob = $this->isPresent($what))) {
            $this->tell("You have no $what.<br />");
            return TRUE;
        }
        if (FALSE === ($env = $this->getEnvironment())
                || !($who_ob = $env->isPresent($who))) {
            $this->tell(ucfirst($who) . ' is not here.<br />');
            return TRUE;
        }

        $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . ' gives ' . $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)
            . ' to ' . $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)
            . '.<br />', $this, $who_ob);
        $who_ob->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . ' gives ' . $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)
            . ' to you.<br />');
        $what_ob->moveDpObject($who_ob);
        $this->tell('You give '
            . $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . ' to '
            . $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE) . '.<br />');
        return TRUE;
    }

    function actionTell($verb, $noun)
    {
        if (empty($noun)) {
            return FALSE;
        }

        if (FALSE === ($pos = strpos($noun, ' ')) || $pos > strlen($noun) - 1) {
            $this->Tell('Tell who what?<br />');
            return TRUE;
        }
        $who = substr($noun, 0, $pos);
        $what = substr($noun, $pos + 1);
        if (FALSE === ($who_ob = get_current_dpuniverse()->findUser($who, $this))) {
            $this->tell("User $who was not found.<br />");
            return TRUE;
        }

        $who_ob->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . " tells you: $what<br />");
        $this->tell("You tell "
            . $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
            . ": $what<br />");

        return TRUE;
    }

    function actionShout($verb, $noun)
    {
        if (empty($noun)) {
            return FALSE;
        }

        $users = get_current_dpuniverse()->getUsers();
        if (sizeof($users)) {
            $from_where = FALSE === ($env = $this->getEnvironment()) ? 'nowhere'
                : $env->getTitle();
            $msg = ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
                . " shouts from $from_where: $noun<br />";
            foreach ($users as &$u) {
                if ($u !== $this) {
                    $u->tell($msg);
                }
            }
        }
        $this->tell("You shout: $noun<br />");
        return TRUE;
    }

    function actionHelp($verb, $noun)
    {
        $this->tell("<window><div id=\"helptext\"><b>Standard commands:</b><br />
Avatar and display settings: <tt>settings, config</tt><br />
Examine stuff: <tt>examine <i>item</i>, look at <i>item</i></tt><br />
Pick up stuff: <tt>take <i>item</i>, take all, get <i>item</i>, get all</tt><br />
What am I carrying?: <tt>inventory, inv, i</tt><br />
Drop stuff: <tt>drop <i>item</i>, drop all</tt><br />
Give stuff to others: <tt>give <i>item</i> to <i>user</i></tt><br />
Say something to users on this page: <tt>say <i>what</i>, '<i>what</i></tt><br />
Tell to another user anywhere: <tt>tell <i>user</i> <i>what</i>, \"<i>user</i> <i>what</i></tt><br />
Shout something to all users on the site: <tt>shout <i>what</i></tt><br />
Emotions: <tt>smile, grin, laugh, shrug, pat, emote</tt><br />
Read something readable: <tt>read <i>item</i></tt><br />
List of people on this site: <tt>who</tt><br />
View source of page: <tt>source</tt><br /><br />
<tt><i>item</i></tt> can be something like <tt>beer, cool beer</tt> or <tt>beer 3</tt> to refer to the third beer. All commands are case insensitive!<br /><br />
Examples: <tt>say hello, tell guest#2 hello, get note, read note, give note to guest#2, drink beer 2</tt><br clear=\"all\" /></div></window>");
        return TRUE;
    }

    function actionSource($verb, $noun)
    {
        if (!strlen($noun)) {
            $what = $this->getEnvironment();
        } else {
            if (FALSE === ($what = $this->isPresent($noun))
                    && FALSE === ($what =
                    $this->getEnvironment()->isPresent($noun))) {
                $this->tell("Can't find: $noun<br />");
                return TRUE;
            }
        }

        if (FALSE === ($what = $this->getEnvironment())) {
            return FALSE;
        }
        /* Without the \n and &nbsp; to &#160 conversion, highlight_file gave
         invalid XHTML */
        $this->tell("<window styleclass=\"dpwindow_src\">\n"
            . str_replace('&nbsp;', '&#160;', highlight_file(DPUNIVERSE_PATH
            . $what->getProperty('location'), TRUE) . "\n</window>"));
        return TRUE;
    }

    function actionLinks($verb, $noun)
    {
        if (!strlen($noun)) {
            $what = $this->getEnvironment();
        } else {
            if (FALSE === ($what = $this->isPresent($noun))
                    && FALSE === ($what =
                    $this->getEnvironment()->isPresent($noun))) {
                $this->tell("Can't find: $noun<br />");
                return TRUE;
            }
        }

        if (FALSE === method_exists($what, 'getExits')
                || 0 === count($links = $what->getExits())) {
            $tell = '<b>No links found in '
                . $what->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
                . '</b><br />';
        } else {
            $tell = '<b>Links found in: ' . $what->getTitle() . '</b><br /><br />';
            foreach ($links as $linktitle => $linkurl) {
                if ($linktitle === DPUNIVERSE_NAVLOGO) {
                    $linkcommand = 'home';
                } else {
                    $linkcommand = explode(' ', $linktitle);
                    $linkcommand = strtolower($linktitle);
                }
                $tell .= "<a href=\"/dpclient.php?location=$linkurl\">"
                    . "$linkcommand</a><br />";
            }
        }
        $this->tell('<window>' . $tell . '</window>');
        return TRUE;
    }

    function actionWho($verb, $noun)
    {
        $users = get_current_dpuniverse()->getUsers();
        if (0 === count($users)) {
            return '<b>No one is on the site.</b><br />';
        }

        $tell = '<b>People currently on this site:</b><br />';
        $tell .= '<table cellpadding="0" cellspacing="0" border="0" style="'
            . 'margin-top: 5px">';
        foreach ($users as &$user) {
            $env = $user->getEnvironment();
            $loc = $env->getProperty('location');
            if (0 !== strpos($loc, 'http://')) {
                $loc = '/dpclient.php?location=' . $loc;
            }
            $env = FALSE === $env ? '-' : '<a href="' . $loc . '">'
                . $env->getTitle() . '</a>';
            $tell .= '<tr><td>' . $user->getTitle()
                . '</td><td style="padding-left: 10px">' . $env . '</td></tr>';
        }
        $tell .= '</table>';
        $this->tell("<window>$tell</window>");
        return TRUE;
    }

    function actionEmotion($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        $usr_msg = $env_msg = $dest_msg = '';
        switch ($verb) {
            case 'smile':
                $usr_msg = 'smile happily.';
                $env_msg = 'smiles happily.';
                break;
            case 'grin':
                $usr_msg = 'grin evilly.';
                $env_msg = 'grins evilly.';
                break;
            case 'laugh':
                $usr_msg = 'fall down on the floor laughing.';
                $env_msg = 'falls down on the floor laughing.';
                break;
            case 'shrug':
                $usr_msg = 'shrug.';
                $env_msg = 'shrugs.';
                break;
            case 'pat':
                if ($noun && !($dest_ob = $env->isPresent($noun))) {
                    $this->tell("Couldn't find: $noun<br />");
                    return TRUE;
                }
                if (!isset($dest_ob)) {
                    $this->tell('Pat who?<br />');
                    return TRUE;
                }
                $usr_msg = 'pat ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . ' on the head with a bone-crushing sound.';
                $env_msg = 'pats ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . ' on the head with a bone-crushing sound.';
                $dest_msg = 'pats you on the head with a bone-crushing sound.';
                break;
            case 'high5':
                if ($noun && !($dest_ob = $env->isPresent($noun))) {
                    $this->tell("Couldn't find: $noun<br />");
                    return TRUE;
                }
                if (!isset($dest_ob)) {
                    $this->tell('High5 who?<br />');
                    return TRUE;
                }
                $usr_msg = 'jump up, and slap a thundering high-five with ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . '.';
                $env_msg = 'jumps up, and slaps a thundering high-five with ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . '.';
                $dest_msg = 'jumps up, and slaps a thundering high-five with you.';
                break;
            case 'hug':
                if ($noun && !($dest_ob = $env->isPresent($noun))) {
                    $this->tell("Couldn't find: $noun<br />");
                    return TRUE;
                }
                if (!isset($dest_ob)) {
                    $this->tell('Hug who?<br />');
                    return TRUE;
                }
                $usr_msg = 'hug ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . '.';
                $env_msg = 'hugs ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . '.';
                $dest_msg = 'hugs you.';
                break;
            case 'kiss':
                if ($noun && !($dest_ob = $env->isPresent($noun))) {
                    $this->tell("Couldn't find: $noun<br />");
                    return TRUE;
                }
                if (!isset($dest_ob)) {
                    $this->tell('Kiss who?<br />');
                    return TRUE;
                }
                $usr_msg = 'give ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . ' a deep and passionate kiss... It seems to last forever...';
                $env_msg = 'give ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . ' a deep and passionate kiss... It seems to last forever...';
                $dest_msg = 'give you a deep and passionate kiss... It seems to last forever...';
                break;
            case 'dance':
                if ($noun && !($dest_ob = $env->isPresent($noun))) {
                    $this->tell("Couldn't find: $noun<br />");
                    return TRUE;
                }
                if (!isset($dest_ob)) {
                    $this->tell('Dance with who?<br />');
                    return TRUE;
                }
                $usr_msg = 'take ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . ' for a dance... The tango!';
                $env_msg = 'takes ' . $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE) . ' for a dance... The tango!';
                $dest_msg = 'takes you for a dance... The tango!';
                break;
            case 'emote':
                if (!strlen($noun)) {
                    $this->tell('Try: emote <i>text</i><br />');
                    return TRUE;
                }
                $this->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)) . " $noun<br />");
                $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)) . " $noun<br />", $this);
                return TRUE;
            default:
                return FALSE;
        }
        $this->tell("You $usr_msg<br />");
        if ($dest_msg !== '') {
            $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)) . " $env_msg<br />", $this, $dest_ob);
            $dest_ob->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)) . " $dest_msg<br />");
        } else {
            $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)) . " $env_msg<br />", $this);
        }
        return TRUE;
    }

    function actionSettings($verb, $noun)
    {
        $this->tell('<script>
var settings_obj = null;

function send_settings()
{
    settings_obj = get_http_obj();
    if (settings_obj) {
        settings_obj.onreadystatechange = rcv_settings;

        var avatar_nr = 1;
        for (i=1;_gel("avatar_nr"+i) != undefined;i++) {
            if (_gel("avatar_nr"+i).checked) {
                avatar_nr = i;
                break;
            }
        }

        settings_obj.open("GET", (str = "/dpclient.php?location=' . $this->getEnvironment()->getProperty('location') . '&rand="+Math.round(Math.random()*9999)
            + "&call_object="+escape("' . $this->getUniqueId() . '")
            + "&method=setSettings"
            + "&avatar_nr="+avatar_nr
            + "&display_mode="+(_gel("display_mode1").checked ? _gel("display_mode1").value : _gel("display_mode2").value)),
            true);
        settings_obj.send(null);
    } else {
        alert("Could not establish connection with server.");
    }
    return false;
}

function rcv_settings()
{
    if (settings_obj.readyState != 4) {
        return;
    }
    if (settings_obj.status == 200) {
        handle_response(settings_obj);
    }
    settings_obj = null;
}
</script>');
        $nr_of_avatars = $this->_getNrOfAvatars();
        $cur_avatar_nr = (int)$this->getProperty('avatar_nr');
        for ($avatar_settings = '', $i = 1; $i <= $nr_of_avatars; $i++) {
            $avatar_settings .= '<input type="radio" id="avatar_nr' . $i
                . '" name="avatar_nr" value="' . $i . '"'
                . ($cur_avatar_nr !== $i ? '' : ' checked="checked"')
                . ' onClick="send_settings()" style="cursor: pointer" />' . $i
                . "&#160; ";
        }

        $this->tell('<window>
Choose your avatar:<br />'
. $avatar_settings . '<br /><br />
People and items on the page are displayed in:<br />
<input type="radio" id="display_mode1" name="display_mode" value="graphical"' . ($this->getProperty('display_mode') == 'graphical' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />Graphical mode<br />
<input type="radio" id="display_mode2" name="display_mode" value="abstract"' . ($this->getProperty('display_mode') == 'abstract' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />Abstract mode<br /><br />
<div id="box"><a href="http://www.messdudes.com/" target="_blank"><b>Mess Dudes</b></a> has kindly allowed DutchPIPE to use a number of avatars.</div>
</window>');
        return TRUE;
    }

    function setSettings()
    {
        if (!isset($this->_GET['avatar_nr'])
                || 0 === strlen($avatar_nr = $this->_GET['avatar_nr'])
                || !isset($this->_GET['display_mode'])
                || 0 === strlen($display_mode = $this->_GET['display_mode'])) {
            $this->tell('Error receiving display mode<br />');
        }

        $this->addProperty('avatar_nr', $avatar_nr);
        $this->setTitleImg(DPUNIVERSE_AVATAR_URL . 'user' . $avatar_nr
            . '.gif');
        $this->setBody('<img src="' . DPUNIVERSE_AVATAR_URL . 'user'
            . $avatar_nr . '_body.gif" border="0" alt="" align="left" '
            . 'style="margin-right: 15px" />A user.<br />');

        $this->addProperty('display_mode', $display_mode);

        if (FALSE !== ($body = $this->getEnvironment()->getAppearanceInventory(0, TRUE,
                NULL, $display_mode))) {
            $this->tell($body);
        }
        $this->getEnvironment()->tell(array('abstract' => '<changeDpElement id="'
            . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE, $this, 'graphical')
            . '</changeDpElement>'), $this);
    }

    function actionSvars($verb, $noun)
    {
        if (!strlen($noun)) {
            $ob =& $this;
        } else {
            if (FALSE === ($env = $this->getEnvironment())) {
                return FALSE;
            }
            if (FALSE === ($ob = $this->isPresent($noun))) {
                if (FALSE === ($ob = $env->isPresent($noun))) {
                    if (FALSE === ($ob = get_current_dpuniverse()->findUser($noun))) {
                        $this->setActionFailure("Target $noun not found.<br />");
                        return FALSE;
                    }
                }
            }
        }
        $this->tell('<window><b>Server variables of '
            . ucfirst($ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . ':</b><br /><pre>' . print_r($ob->_SERVER, TRUE) . '</pre>'
            . '<b>Properties</b></pre>' . print_r($ob->getProperties(), TRUE)
            . '</pre></window>');
        return TRUE;
    }

    function actionForce($verb, $noun)
    {
        if (!strlen($noun = trim($noun))
                || FALSE === ($pos = strpos($noun, ' '))) {
            $this->setActionFailure("Syntax: force <i>who what</i>.<br />");
            return FALSE;
        }
        $who = substr($noun, 0, $pos);
        $what = substr($noun, $pos + 1);
        if (FALSE === ($who_ob = $this->isPresent($who))) {
            if (FALSE !== ($env = $this->getEnvironment())) {
                $who_ob = $env->isPresent($who);
            }
        }
        if (FALSE === $who_ob) {
            $this->setActionFailure("Target $who not found.<br />");
            return FALSE;
        }

        $this->tell('You give '
            . $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
            . ' the old "Jedi mind-trick" stink eye.<br />');
        $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . ' gives ' . $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
            . ' the old "Jedi mind-trick" stink eye.<br />', $this, $who_ob);
        $who_ob->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . ' gives you the old "Jedi mind-trick" stink eye.<br />');

        $who_ob->performAction($what);
        return TRUE;
    }
}
?>
