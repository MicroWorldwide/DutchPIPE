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
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpLiving.php 45 2006-06-20 12:38:26Z ls $
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
 * @subpackage dpuniverse_std
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
     * Message to be shown when an action fails, can be set by actions
     *
     * @var         string
     * @access      private
     * @see         setActionFailure(), getActionFailure(),
     *              $mActionDefaultFailure
     */
    private $mActionFailure = DPUNIVERSE_ACTION_DEFAULT_FAILURE;

    /**
     * Default message to be shown when an action fails
     *
     * @var         string
     * @access      private
     * @see         setActionDefaultFailure(), getActionDefaultFailure(),
     *              $mActionFailure
     */
    private $mActionDefaultFailure = DPUNIVERSE_ACTION_DEFAULT_FAILURE;

    /**
     * Creates this living object
     *
     * Called by DpObject when this object is created. Adds standard actions
     * which can be performed by this object, usually a user or a computer
     * controlled character.
     *
     * Calls {@link createDpLiving()} in the inheriting class.
     *
     * Adds a "is_living" property to this object, set to TRUE.
     *
     * Starts a "heartbeat", see {@link timeoutHeartBeat()}.
     *
     * @access     private
     * @see        createDpLiving(), timeoutHeartBeat()
     */
    final function createDpObject()
    {
        $this->addProperty('is_living');
        $this->setBody(dptext("This description hasn't been set yet.<br />"));

        /* Actions for everybody */
        $this->addAction(dptext('inventory'), explode('#', dptext('inventory#inv#i')), 'actionInventory', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('examine'), explode('#', dptext('examine#exam#exa#x#look#l')), 'actionExamine', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF | DP_ACTION_TARGET_LIVING | DP_ACTION_TARGET_OBJINV | DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext("who's here?"), dptext('who'), 'actionWho', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('take'), explode('#', dptext('take#get')), 'actionTake', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('drop'), dptext('drop'), 'actionDrop', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_OBJINV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('say'), dptext('say'), 'actionSay', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('help'), dptext('help'), 'actionHelp', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('give...'), dptext('give'), 'actionGive', array($this, 'actionGiveOperant'), DP_ACTION_TARGET_OBJINV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('tell'), dptext('tell'), 'actionTell', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('shout'), dptext('shout'), 'actionShout', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('settings'), explode('#', dptext('settings#config')), 'actionSettings', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('source'), dptext('source'), 'actionSource', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('page links'), dptext('links'), 'actionLinks', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        $this->addAction(dptext('smile'), dptext('smile'), 'actionSmile', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('grin'), dptext('grin'), 'actionGrin', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('laugh'), dptext('laugh'), 'actionLaugh', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('shrug'), dptext('shrug'), 'actionShrug', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('pat'), dptext('pat'), 'actionPat', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('high five'), dptext('high5'), 'actionHighFive', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('hug'), dptext('hug'), 'actionHug', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('kiss'), dptext('kiss'), 'actionKiss', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('dance'), dptext('dance'), 'actionDance', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('emote'), dptext('emote'), 'actionEmote', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        /* Actions for admin only */
        $this->addAction(dptext('svars'), dptext('svars'), 'actionSvars', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('force'), dptext('force'), 'actionForce', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('move!'), dptext('move!'), 'actionMove', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->setTimeout('timeoutHeartBeat', 2);

        $this->createDpLiving();
    }

    /**
     * Sets this living object up at the time it is created
     *
     * An empty function which can be redefined by the living class extending
     * on DpLiving. When the object is created, it has no title, HTML body, et
     * cetera, so in this method methods like {@link DpObject::setTitle()} are
     * called. Building blocks extending on DpLiving may define their own create
     * function. For example, DpNpc defines {@link DpNpc:createDpNpc}.
     *
     * @see        resetDpLiving()
     */
    function createDpLiving()
    {
    }

    /**
     * Resets this living object
     *
     * Called by DpObject at regular intervals as defined in dpuniverse-ini.php.
     * Calls the method 'resetDpLiving' in this living object. You can redefine
     * that function to periodically do stuff such as alter the state of this
     * living object.
     *
     * @access     private
     * @see        resetDpLiving()
     */
    final function resetDpObject()
    {
        $this->resetDpLiving();
    }

    /**
     * Resets this living object
     *
     * Called by this living object at regular intervals as defined in
     * dpuniverse-ini.php. An empty function which can be redefined by the
     * living class extending on DpLiving. To be used to periodically do stuff
     * such as alter the state of the living object.
     *
     * @see        createDpLiving()
     */
    function resetDpLiving()
    {
    }

    /**
     * Calls itself every "heartbeat"
     *
     * Redefine this method to make timed stuff happen.
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
     *
     * @access  private
     * @return  int     the number of available avatar images
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
     * Call this method from action methods, for example actionFoo(). When the
     * user performs an action but the action fails, there are two ways for the
     * method implementing the action to communicate the failure to the user.
     *
     * <ol>
     * <li>
     * Call {@link DpUser:tell()} in the user and return TRUE, for
     * example:<br><br>
     * <code>
     *$user->tell('Action foo failed because of bar.');
     *return TRUE;
     * </code>
     * <br>
     * The action system will stop looking for other ways to perform the
     * action.<br><br>
     * </li>
     * <li>
     * Call this setActionFailure method in the user and return FALSE. This
     * failure setup is used when different objects can have the same actions
     * implemented with addAction - if one fails, another might still
     * succeed. Example:<br><br>
     * <code>
     *$user->setActionFailure('Action foo failed because of bar.');
     *return FALSE;
     * </code>
     * <br>
     * The action system will continue looking for other ways to perform
     * the action. If it doesn't find any, the previously set failure message
     * is communicated to the user. Otherwise, the next method implementing
     * the action takes over. The action system will continue looking for
     * object/methods implementing the action, until TRUE is returned or no
     * more implementations are found. In that case, the last set action
     * failure is returned. If no action failure was set, the default failure
     * message is shown, see {@link setActionDefaultFailure()}.
     * </li>
     * </ol>
     *
     * @param   string  $actionFailure message to be shown when an action fails
     * @example /home/ls/dev.dutchpipe/dpuniverse/obj/note.php A readable note
     * @see     getActionFailure(), setActionDefaultFailure(),
     *          getActionDefaultFailure()
     */
    final public function setActionFailure($actionFailure)
    {
        $this->mActionFailure = $actionFailure;
    }

    /**
     * Gets the message to be shown when an action fails
     *
     * @return  string  message to be shown when an action fails
     * @see     setActionFailure(), setActionDefaultFailure(),
     *          getActionDefaultFailure()
     */
    final public function getActionFailure()
    {
        return $this->mActionFailure;
    }

    /**
     * Sets the default message shown to the user when an action fails
     *
     * Used when no message was set with setActionFailure, for example "What?".
     *
     * @param   string  $actionDefaultFailure default message to be shown when
     *                                        an action fails
     * @see     setActionFailure(), getActionFailure(),
     *          getActionDefaultFailure()
     */
    final public function setActionDefaultFailure($actionDefaultFailure)
    {
        $this->mActionDefaultFailure = $actionDefaultFailure;
    }

    /**
     * Gets the default message shown to the user when an action fails
     *
     * Used when no message was set with setActionFailure, for example "What?".
     *
     * @return  string  default message to be shown when an action fails
     * @see     setActionFailure(), getActionFailure(),
     *          setActionDefaultFailure()
     */
    final public function getActionDefaultFailure()
    {
        return $this->mActionDefaultFailure;
    }

    /**
     * Tries to perform the action given by the living object
     *
     * Called by the system to handle both actions performed by clicking on
     * menus and actions from the input area. The first method will result in an
     * $action parameter such as "take object#242" (using an unique object id).
     * With the second method, $action will contain what the user typed, such as
     * "take beer".
     *
     * Searches and calls for the right method linked to the action, which could
     * be in another object. Handles failure messages when the action failed.
     *
     * This method can also be used to force living objects to perform actions.
     *
     * @param   string  $action     the action given by the living object
     * @return  boolean TRUE for success, FALSE for unsuccessful action
     * @see     setActionFailure(), getActionFailure(), setActionDefaultFailure(),
     *          getActionDefaultFailure(), DpObject::addAction(),
     *          DpObject::getActions(), DpObject::getActionsMenu(),
     *          DpObject::getTargettedActions(),
     *          DpObject::performActionSubject()
     */
    final public function performAction($action)
    {
        global $grCurrentDpObject;

        $action = trim($action);
        $grCurrentDpObject = $this;

        echo sprintf(dptext("Action by %s:%s\n"), $this->getTitle(), $action);

        $rval = (bool)$this->performActionSubject($action, $this);
        if (TRUE !== $rval) {
            if (strlen($action) && $action !== dptext('look')
                    && $action !== dptext('l')) {
                $this->tell($this->getActionFailure());
                $this->setActionFailure($this->getActionDefaultFailure());

                if ($this->getProperty('is_user')
                        && $this === get_current_dpuser()) {
                    get_current_dpuniverse()->
                        mrCurrentDpUserRequest->mToldSomething = TRUE;
                }
            }
        }
        $grCurrentDpObject = FALSE;
        return $rval;
    }

    /**
     * Makes this living object examine an object
     *
     * @param   string  $verb       the action, "examine"
     * @param   string  $noun       what to examine, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionExamine($verb, $noun)
    {
        if ($verb == dptext('look') || $verb == dptext('l')) {
            $at = dptext('at');
            $at_len = strlen($at);
            if (strlen($noun) >= $at_len && substr($noun, 0, $at_len) == $at) {
                $noun = $noun == $at ? '' : trim(substr($noun, $at_len));
            }
        }

        if (!strlen($noun)) {
            $this->setActionFailure($verb == dptext('look')
                || $verb == dptext('l')
                ? dptext('Look at what?<br />')
                : dptext('Examine what?<br />'));
            return FALSE;
        }

        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (!($ob = $this->isPresent($noun))) {
            if (!($ob = $env->isPresent($noun))) {
                $this->setActionFailure(sprintf(
                    dptext('There is no %s here.<br />'), $noun));
                return FALSE;
            }
        }

        $this->tell('<window>' . $ob->getAppearance(0, TRUE, NULL,
            $this->getProperty('display_mode'), FALSE, 'dpobinv')
            . '</window>');
        return TRUE;
    }

    /**
     * Makes this living object take an object
     *
     * @param   string  $verb       the action, "take"
     * @param   string  $noun       what to take, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionTake($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        if (!strlen($noun)) {
            $this->setActionFailure(ucfirst(sprintf(dptext('%s what?<br />'),
                $noun)));
            return FALSE;
        }

        if ($noun == dptext('all')) {
            $inv = $env->getInventory();
            $picked_up = FALSE;
            foreach ($inv as &$ob) {
                if (FALSE === $ob->getProperty('is_living')) {
                    $ob->moveDpObject($this);
                    $picked_up = TRUE;
                    $this->tell(sprintf(dptext('You take %s.<br />'),
                        $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
                    $env->tell(ucfirst(sprintf(dptext('%s takes %s.<br />'),
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                        $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))),
                        $this);
                }
            }
            if (FALSE === $picked_up) {
               $this->tell(dptext('There is nothing to pick up here.<br />'));
            }
            return TRUE;
        }

        if (FALSE === ($ob = $env->isPresent($noun))) {
            $this->setActionFailure(sprintf(
                dptext('There is no %s here.<br />'), $noun));
            return FALSE;
        }

        if (FALSE !== $ob->getProperty('is_living')) {
            $this->tell(ucfirst(sprintf(dptext('%s refuses to be taken.<br />'),
                $ob->getTitle())));
            return TRUE;
        }

        $ob->moveDpObject($this);
        $this->tell(sprintf(dptext('You take %s.<br />'),
            $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(dptext('%s takes %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))),
            $this);
        return TRUE;
    }

    /**
     * Drops an object in the user's environment
     *
     * @param   string  $verb       the action, "drop"
     * @param   string  $noun       what to drop, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionDrop($verb, $noun, $silently = FALSE)
    {
        if (!strlen($noun)) {
            if (FALSE === $silently) {
                $this->setActionFailure(dptext('Drop what?<br />'));
            }
            return FALSE;
        }
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        if ($noun == dptext('all')) {
            $inv = $this->getInventory();
            if (sizeof($inv) == 0) {
               if (FALSE === $silently) {
                   $this->tell(dptext('You have nothing to drop.<br />'));
               }
               return TRUE;
            }
            foreach ($inv as &$ob) {
                $ob->moveDpObject($env);
                if (FALSE === $silently) {
                    $this->tell(sprintf(dptext('You drop %s.<br />'),
                        $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
                }
                $env->tell(ucfirst(sprintf(dptext('%s drops %s.<br />'),
                    $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                    $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))),
                    $this);
            }
            return TRUE;
        }

        if (FALSE === ($ob = $this->isPresent($noun))) {
            if (FALSE === $silently) {
                $this->setActionFailure(sprintf(
                    dptext('There is no %s here.<br />'), $noun));
            }
            return FALSE;
        }

        $ob->moveDpObject($env);
        if (FALSE === $silently) {
            $this->tell(sprintf(dptext('You drop %s.<br />'),
                $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        }

        $env->tell(ucfirst(sprintf(dptext('%s drops %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))),
            $this);
        return TRUE;
    }

    /**
     * Shows this living object a list of objects it is carrying
     *
     * @param   string  $verb       the action, "inventory"
     * @param   string  $noun       empty string
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionInventory($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }

        $inventory = $this->getAppearance(0, TRUE, NULL,
            $this->getProperty('display_mode'), -1, 'dpobinv');
        /* :KLUDGE: */
        $carrying_str = dptext('You are carrying:');
        $inventory = str_replace($carrying_str, "<b>$carrying_str</b>",
            $inventory);
        $this->tell("<window>$inventory</window>");
        return TRUE;
    }

    /**
     * Makes this living object say something
     *
     * @param   string  $verb       the action, "say"
     * @param   string  $noun       what to say, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionSay($verb, $noun)
    {
        if (empty($noun)) {
            $this->tell(dptext('Say what?<br />'));
            return TRUE;
        }
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        $env->tell(ucfirst(sprintf(dptext('%s says: %s<br />'),
            $this->getTitle(), $noun)), $this);
        $this->tell(sprintf(dptext('You say: %s<br />'), $noun));
        return TRUE;
    }

    /**
     * Completes the give action performed by clicking on an object
     *
     * Called by the action system when someone clicks on an object and selects
     * "give...". Returns something like "beer to", which allows the system to
     * fill the input area with "give beer to " using the title of the object
     * which was clicked on.
     *
     * @param   string  $verb       the action, "give"
     * @return  string  a string such as "beer to "
     * @see     actionGive()
     */
    function actionGiveOperant($menuobj)
    {
        return sprintf(dptext('%s to '), strtolower($menuobj->getTitle()));
    }

    /**
     * Makes this living object give an object to another living object
     *
     * @param   string  $verb       the action, "give"
     * @param   string  $noun       what and who to give, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     * @see     actionGiveOperant()
     */
    function actionGive($verb, $noun)
    {
        if (empty($noun)) {
            return FALSE;
        }
        $to = ' ' . dptext('to') . ' ';
        $to_len = strlen($to);
        if (FALSE === ($pos = strpos($noun, $to))
                || $pos > strlen($noun) - $to_len) {
            $this->Tell(dptext('Give what to who?<br />'));
            return TRUE;
        }
        $what = substr($noun, 0, $pos);
        $who = substr($noun, $pos + $to_len);
        if (!($what_ob = $this->isPresent($what))) {
            $this->tell(sprintf(dptext('You have no %s.<br />'), $hwat));
            return TRUE;
        }
        if (FALSE === ($env = $this->getEnvironment())
                || !($who_ob = $env->isPresent($who))) {
            $this->tell(ucfirst(sprintf(dptext('%s is not here.<br />'),
                $who)));
            return TRUE;
        }

        $env->tell(ucfirst(sprintf(dptext('%s gives %s to %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE),
            $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))),
            $this, $who_ob);
        $who_ob->tell(ucfirst(sprintf(dptext('%s gives %s to you.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))));
        $what_ob->moveDpObject($who_ob);
        $this->tell(sprintf(dptext('You give %s to %s.<br />'),
            $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)));
        return TRUE;
    }

    /**
     * Makes this living object tell something to another living object
     *
     * @param   string  $verb       the action, "tell"
     * @param   string  $noun       who and what to tell, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionTell($verb, $noun)
    {
        if (empty($noun)) {
            return FALSE;
        }

        if (FALSE === ($pos = strpos($noun, ' ')) || $pos > strlen($noun) - 1) {
            $this->Tell(dptext('Tell who what?<br />'));
            return TRUE;
        }
        $who = substr($noun, 0, $pos);
        $what = substr($noun, $pos + 1);
        if (FALSE === ($who_ob = get_current_dpuniverse()->findUser($who))) {
            $this->tell(sprintf(dptext('User %s was not found.<br />'), $who));
            return TRUE;
        }

        $who_ob->tell(ucfirst(sprintf(dptext('%s tells you: %s<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE), $what)));
        $this->tell(sprintf(dptext('You tell %s: %s<br />'),
            $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE), $what));

        return TRUE;
    }

    /**
     * Makes this living object shout something to everyone on the site
     *
     * @param   string  $verb       the action, "shout"
     * @param   string  $noun       what to shout, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionShout($verb, $noun)
    {
        if (empty($noun)) {
            return FALSE;
        }

        $users = get_current_dpuniverse()->getUsers();
        if (sizeof($users)) {
            $msg = FALSE === ($env = $this->getEnvironment())
                ? ucfirst(sprintf(dptext('%s shouts from nowhere: %s<br />'),
                    $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE), $noun))
                : ucfirst(sprintf(dptext('%s shouts from %s: %s<br />'),
                    $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                    $env->getTitle(), $noun));
            foreach ($users as &$u) {
                if ($u !== $this) {
                    $u->tell($msg);
                }
            }
        }
        $this->tell(sprintf(dptext('You shout: %s<br />'), $noun));
        return TRUE;
    }

    /**
     * Shows this living object a window with help information
     *
     * @param   string  $verb       the action, "help"
     * @param   string  $noun       empty string
     * @return  boolean TRUE
     */
    function actionHelp($verb, $noun)
    {
        $this->tell("<window><div id=\"helptext\">"
            . dptext("<b>Standard commands:</b><br />
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
Examples: <tt>say hello, tell guest#2 hello, get note, read note, give note to guest#2, drink beer 2</tt>")
            . "<br clear=\"all\" /></div></window>");
        return TRUE;
    }

    /**
     * Shows this living object source code of environment or of another object
     *
     * @param   string  $verb       the action, "source"
     * @param   string  $noun       what to show source of, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionSource($verb, $noun)
    {
        if (!strlen($noun)) {
            $what = $this->getEnvironment();
        } else {
            if (FALSE === ($what = $this->isPresent($noun))
                    && FALSE === ($what =
                    $this->getEnvironment()->isPresent($noun))) {
                $this->tell(sprintf(dptext("Can't find: %s<br />"), $noun));
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

    /**
     * Shows this living a list of links in its environment or in another object
     *
     * @param   string  $verb       the action, "links"
     * @param   string  $noun       what to take, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionLinks($verb, $noun)
    {
        if (!strlen($noun)) {
            $what = $this->getEnvironment();
        } else {
            if (FALSE === ($what = $this->isPresent($noun))
                    && FALSE === ($what =
                    $this->getEnvironment()->isPresent($noun))) {
                $this->tell(sprintf(dptext("Can't find: %s<br />"), $noun));
                return TRUE;
            }
        }

        if (FALSE === method_exists($what, 'getExits')
                || 0 === count($links = $what->getExits())) {
            $tell = '<b>' . sprintf(dptext('No links found in %s'),
                $what->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
                . '</b><br />';
        } else {
            $tell = '<b>' . sprintf(dptext('Links found in: %s'),
                $what->getTitle()) . '</b><br /><br />';
            foreach ($links as $linktitle => $linkurl) {
                if ($linktitle === DPUNIVERSE_NAVLOGO) {
                    $linkcommand = dptext('home');
                } else {
                    $linkcommand = explode(' ', $linktitle);
                    $linkcommand = strtolower($linktitle);
                }
                $tell .= "<a href=\"" . DPSERVER_CLIENT_URL
                    . "?location=$linkurl\">$linkcommand</a><br />";
            }
        }
        $this->tell('<window>' . $tell . '</window>');
        return TRUE;
    }

    /**
     * Shows this living object a list of users on the site
     *
     * @param   string  $verb       the action, "who"
     * @param   string  $noun       empty string
     * @return  boolean TRUE
     */
    function actionWho($verb, $noun)
    {
        $users = get_current_dpuniverse()->getUsers();
        if (0 === count($users)) {
           $this->tell('<window><b>' . dptext('No one is on this site.')
            . '</b></window>');
            return TRUE;
        }

        $tell = '<b>' . dptext('People currently on this site:') . '</b><br />';
        $tell .= '<table cellpadding="0" cellspacing="0" border="0" style="'
            . 'margin-top: 5px">';
        foreach ($users as &$user) {
            $env = $user->getEnvironment();
            $loc = $env->getProperty('location');
            if (0 !== strpos($loc, 'http://')) {
                $loc = DPSERVER_CLIENT_URL . '?location=' . $loc;
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

    /**
     * Makes this living object smile happily
     *
     * @param   string  $verb       the action, "smile"
     * @param   string  $noun       empty string
     * @return  boolean TRUE
     */
    function actionSmile($verb, $noun)
    {
        $this->tell(dptext('You smile happily.<br />'));
        if (FALSE !== ($env = $this->getEnvironment())) {
            $env->tell(ucfirst(sprintf(dptext('%s smiles happily.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))), $this);
        }
        return TRUE;
    }

    /**
     * Makes this living object grin evilly
     *
     * @param   string  $verb       the action, "grin"
     * @param   string  $noun       empty string
     * @return  boolean TRUE
     */
    function actionGrin($verb, $noun)
    {
        $this->tell(dptext('You grin evilly.<br />'));
        if (FALSE !== ($env = $this->getEnvironment())) {
            $env->tell(ucfirst(sprintf(dptext('%s grins evilly.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))), $this);
        }
        return TRUE;
    }

    /**
     * Makes this living object fall down on the floor laughing
     *
     * @param   string  $verb       the action, "laugh"
     * @param   string  $noun       empty string
     * @return  boolean TRUE
     */
    function actionLaugh($verb, $noun)
    {
        $this->tell(dptext('You fall down on the floor laughing.<br />'));
        if (FALSE !== ($env = $this->getEnvironment())) {
            $env->tell(ucfirst(sprintf(
                dptext('%s falls down on the floor laughing.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))), $this);
        }
        return TRUE;
    }

    /**
     * Makes this living object shrug
     *
     * @param   string  $verb       the action, "laugh"
     * @param   string  $noun       empty string
     * @return  boolean TRUE
     */
    function actionShrug($verb, $noun)
    {
        $this->tell(dptext('You shrug.<br />'));
        if (FALSE !== ($env = $this->getEnvironment())) {
            $env->tell(ucfirst(sprintf(dptext('%s shrugs.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))), $this);
        }
        return TRUE;
    }

    /**
     * Makes this living object pat another living object on the head
     *
     * @param   string  $verb       the action, "pat"
     * @param   string  $noun       who to pat, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionPat($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment()) ||
                ($noun && !($dest_ob = $env->isPresent($noun)))) {
            $this->setActionFailure(sprintf(dptext("Couldn't find: %s<br />"),
                $noun));
            return FALSE;
        }
        if (!isset($dest_ob)) {
            $this->setActionFailure(dptext('Pat who?<br />'));
            return FALSE;
        }
        $this->tell(sprintf(
            dptext('You pat %s on the head with a bone-crushing sound.<br />'),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dptext('%s pats %s on the head with a bone-crushing sound.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $dest_ob);
        $dest_ob->tell(ucfirst(sprintf(
            dptext('%s pats you on the head with a bone-crushing sound.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        return TRUE;
    }

    /**
     * Makes this living object slap a high-five with another living object
     *
     * @param   string  $verb       the action, "high5"
     * @param   string  $noun       who to high5, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionHighFive($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment()) ||
                ($noun && !($dest_ob = $env->isPresent($noun)))) {
            $this->setActionFailure(sprintf(dptext("Couldn't find: %s<br />"),
                $noun));
            return FALSE;
        }
        if (!isset($dest_ob)) {
            $this->setActionFailure(dptext('High5 who?<br />'));
            return FALSE;
        }
        $this->tell(sprintf(
            dptext('You jump up, and slap a thundering high-five with %s.<br />'),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dptext('%s jumps up, and slaps a thundering high-five with %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $dest_ob);
        $dest_ob->tell(ucfirst(sprintf(
            dptext('%s jumps up, and slaps a thundering high-five with you.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        return TRUE;
    }

    /**
     * Makes this living object hug another living object
     *
     * @param   string  $verb       the action, "hug"
     * @param   string  $noun       who to hug, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionHug($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment()) ||
                ($noun && !($dest_ob = $env->isPresent($noun)))) {
            $this->setActionFailure(sprintf(dptext("Couldn't find: %s<br />"),
                $noun));
            return FALSE;
        }
        if (!isset($dest_ob)) {
            $this->setActionFailure(dptext('Hug who?<br />'));
            return FALSE;
        }
        $this->tell(sprintf(dptext('You hug %s.<br />'),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(dptext('%s hugs %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $dest_ob);
        $dest_ob->tell(ucfirst(sprintf(dptext('%s hugs you.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        return TRUE;
    }

    /**
     * Makes this living object give another living object a passionate kiss
     *
     * @param   string  $verb       the action, "kiss"
     * @param   string  $noun       who to kiss, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionKiss($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment()) ||
                ($noun && !($dest_ob = $env->isPresent($noun)))) {
            $this->setActionFailure(sprintf(dptext("Couldn't find: %s<br />"),
                $noun));
            return FALSE;
        }
        if (!isset($dest_ob)) {
            $this->setActionFailure(dptext('Kiss who?<br />'));
            return FALSE;
        }
        $this->tell(sprintf(
            dptext('You give %s a deep and passionate kiss... It seems to last forever...<br />'),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dptext('%s gives %s a deep and passionate kiss... It seems to last forever...<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $dest_ob);
        $dest_ob->tell(ucfirst(sprintf(
            dptext('%s gives you a deep and passionate kiss... It seems to last forever...<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        return TRUE;
    }

    /**
     * Makes this living object take another living object for a dance
     *
     * @param   string  $verb       the action, "dance"
     * @param   string  $noun       who to dance with, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionDance($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment()) ||
                ($noun && !($dest_ob = $env->isPresent($noun)))) {
            $this->setActionFailure(sprintf(dptext("Couldn't find: %s<br />"),
                $noun));
            return FALSE;
        }
        if (!isset($dest_ob)) {
            $this->setActionFailure(dptext('Dance with who?<br />'));
            return FALSE;
        }
        $this->tell(sprintf(
            dptext('You take %s for a dance... The tango!<br />'),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dptext('%s takes %s for a dance... The tango!<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $dest_ob);
        $dest_ob->tell(ucfirst(sprintf(
            dptext('%s takes you for a dance... The tango!<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        return TRUE;
    }

    /**
     * Makes this living object communicate a custom message to its environment
     *
     * @param   string  $verb       the action, "emote"
     * @param   string  $noun       string to "emote"
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionEmote($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment())) {
            return FALSE;
        }
        if (!strlen($noun)) {
            $this->setActionFailure(dptext('Try: emote <i>text</i><br />'));
            return FALSE;
        }
        $this->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . " $noun<br />");
        $env->tell(ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . " $noun<br />", $this);
        return TRUE;
    }

    /**
     * Shows this living object a window with settings
     *
     * @param   string  $verb       the action, "settings"
     * @param   string  $noun       empty string
     * @return  boolean TRUE for action completed, FALSE otherwise
     * @see     setSettings()
     */
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

        settings_obj.open("GET", (str = "' . DPSERVER_CLIENT_URL . '?location='
            . $this->getEnvironment()->getProperty('location')
            . '&rand="+Math.round(Math.random()*9999)
            + "&call_object="+escape("' . $this->getUniqueId() . '")
            + "&method=setSettings"
            + "&avatar_nr="+avatar_nr
            + "&display_mode="+(_gel("display_mode1").checked ? _gel("display_mode1").value : _gel("display_mode2").value)),
            true);
        settings_obj.send(null);
    } else {
        alert("' . dptext('Could not establish connection with server.') . '");
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

        $this->tell('<window>' . dptext('Choose your avatar:') . '<br />'
. $avatar_settings . '<br /><br />'
. dptext('People and items on the page are displayed in:') . '<br />
<input type="radio" id="display_mode1" name="display_mode" value="graphical"' . ($this->getProperty('display_mode') == 'graphical' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dptext('Graphical mode') . '<br />
<input type="radio" id="display_mode2" name="display_mode" value="abstract"' . ($this->getProperty('display_mode') == 'abstract' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dptext('Abstract mode') . '<br /><br />
<div id="box"><a href="http://www.messdudes.com/" target="_blank"><b>Mess Dudes</b></a> has kindly allowed DutchPIPE to use a number of avatars.</div>
</window>');
        return TRUE;
    }

    /**
     * Applies settings from the settings menu obtained with actionSettings()
     *
     * Called from the Javascript that goes with the menu that pops up after the
     * settings action has been performed. Applies avatar and dispay mode
     * settings, based on the DpUser::_GET variable in this living object.
     *
     * @see     actionSettings
     * @todo    save settings for registered users in the database
     */
    function setSettings()
    {
        if (!isset($this->_GET['avatar_nr'])
                || 0 === strlen($avatar_nr = $this->_GET['avatar_nr'])
                || !isset($this->_GET['display_mode'])
                || 0 === strlen($display_mode = $this->_GET['display_mode'])) {
            $this->tell(dptext('Error receiving display mode.<br />'));
        }

        $this->addProperty('avatar_nr', $avatar_nr);
        $this->setTitleImg(DPUNIVERSE_AVATAR_URL . 'user' . $avatar_nr
            . '.gif');
        $this->setBody('<img src="' . DPUNIVERSE_AVATAR_URL . 'user'
            . $avatar_nr . '_body.gif" border="0" alt="" align="left" '
            . 'style="margin-right: 15px" />' . dptext('A user.') . '<br />');

        $this->addProperty('display_mode', $display_mode);

        if (FALSE !== ($body = $this->getEnvironment()->
                getAppearanceInventory(0, TRUE, NULL, $display_mode))) {
            $this->tell($body);
        }
        $this->getEnvironment()->tell(array('abstract' =>
            '<changeDpElement id="'
            . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE, $this, 'graphical')
            . '</changeDpElement>'), $this);
    }

    /**
     * Shows this administrator various PHP/server information about a user
     *
     * @param   string  $verb       the action, "svars"
     * @param   string  $noun       who to show info of, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
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
                    if (FALSE ===
                            ($ob = get_current_dpuniverse()->findUser($noun))) {
                        $this->setActionFailure(sprintf(
                            dptext('Target %s not found.<br />'), $noun));
                        return FALSE;
                    }
                }
            }
        }
        $this->tell('<window><b>' . sprintf(dptext('Server variables of %s:'),
            $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . '</b><br /><pre>' . print_r($ob->_SERVER, TRUE) . '</pre>'
            . '<b>' . sprintf(dptext('Properties of %s:'),
            $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . '</b><pre>' . print_r($ob->getProperties(), TRUE)
            . '</pre></window>');
        return TRUE;
    }

    /**
     * Makes this administrator force another living object to perform an action
     *
     * @param   string  $verb       the action, "force"
     * @param   string  $noun       who and what to force
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionForce($verb, $noun)
    {
        if (!strlen($noun = trim($noun))
                || FALSE === ($pos = strpos($noun, ' '))) {
            $this->setActionFailure(
                dptext('Syntax: force <i>who what</i>.<br />'));
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
            $this->setActionFailure(sprintf(
                dptext('Target %s not found.<br />'), $who));
            return FALSE;
        }

        $this->tell(sprintf(
            dptext('You give %s the old "Jedi mind-trick" stink eye.<br />'),
            $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dptext('%s gives %s the old "Jedi mind-trick" stink eye.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $who_ob);
        $who_ob->tell(ucfirst(sprintf(
            dptext('%s gives you the old "Jedi mind-trick" stink eye.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        $who_ob->performAction($what);
        return TRUE;
    }

    /**
     * Makes this administrator move an object to another environment
     *
     * @param   string  $verb       the action, "move"
     * @param   string  $noun       what and where to move
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionMove($verb, $noun)
    {
        if (!strlen($noun = trim($noun))
                || FALSE === ($pos = strpos($noun, ' '))) {
            $this->setActionFailure(
                dptext('Syntax: move <i>what where</i>.<br />'));
            return FALSE;
        }

        $what = substr($noun, 0, $pos);
        if (FALSE === ($what_ob = $this->isPresent($what))) {
            if (FALSE !== ($env = $this->getEnvironment())) {
                $what_ob = $env->isPresent($what);
            }
            if (FALSE === $what_ob) {
                $what_ob = get_current_dpuniverse()->findUser($what);
            }
        }
        if (FALSE === $what_ob) {
            $this->setActionFailure(sprintf(
                dptext('Object to move %s not found.<br />'), $what));
            return FALSE;
        }

        $where = substr($noun, $pos + 1);
        $env = $this->getEnvironment();
        if ('!' === $where) {
            $where_ob = $this->getEnvironment();
            if (FALSE === $where_ob) {
                $this->setActionFailure(sprintf(
                    dptext("Can't move object %s to this location: you have no environment.<br />"),
                    $what));
            }
        }
        elseif ('me' === $where) {
            $where_ob = $this;
        }
        elseif (FALSE === ($where_ob = $this->isPresent($where))) {
            if (FALSE !== $env) {
                $where_ob = $env->isPresent($where);
            }
            if (FALSE === $where_ob) {
                $where_ob = get_current_dpuniverse()->findUser($where);
            }
        }
        if (FALSE === $where_ob) {
            $this->setActionFailure(sprintf(
                dptext('Target %s not found.<br />'), $where));
            return FALSE;
        }

        if ($this === $what_ob && $this === $where_ob) {
            $this->setActionFailure(
                dptext('You cannot move yourself into yourself.<br />'));
            return FALSE;
        }

        $this->tell(sprintf(
            dptext('You give %s the old "Jedi mind-trick" stink eye.<br />'),
            $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        if (FALSE !== $env) {
            $env->tell(ucfirst(sprintf(
                dptext('%s gives %s the old "Jedi mind-trick" stink eye.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
                $this, $what_ob);
            }
        $what_ob->tell(ucfirst(sprintf(
            dptext('%s gives you the old "Jedi mind-trick" stink eye.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        $what_ob->moveDpObject($where_ob);
        return TRUE;
    }
}
?>
