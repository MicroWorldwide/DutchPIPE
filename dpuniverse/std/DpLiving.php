<?php
/**
 * An object which is "alive", common code shared between users and NPCs
 *
 * DutchPIPE version 0.3; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpLiving.php 252 2007-08-02 23:30:58Z ls $
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
 * Creates the following DutchPIPE properties:<br />
 *
 * - boolean <b>isLiving</b> - Set to TRUE
 * - string <b>displayMode</b> - "graphical" or "abstract"
 * - integer <b>sessionAge</b> - Age in seconds of this object
 * - integer <b>weightCarry</b> - Combined weight of objects in our inventory
 * - integer <b>maxWeightCarry</b> - Maximum weight this object can carry
 * - integer <b>volumeCarry</b> - Combined volume of objects in our inventory
 * - integer <b>maxVolumeCarry</b> - Maximum volume this object can carry
 * - string <b>actionFailure</b> - Last action error message, "Read what?"
 * - string <b>actionDefaultFailure</b> - Default action error message, "What?"
 * - int <b>lastActionTime</b> - UNIX time stamp of last action performed
 * - string <b>inputMode</b> - Input area mode, 'say' or 'cmd'
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */
class DpLiving extends DpObject
{
    /**
     * Creates this living object
     *
     * Called by DpObject when this object is created. Adds standard actions
     * which can be performed by this object, usually a user or a computer
     * controlled character.
     *
     * Calls {@link createDpLiving()} in the inheriting class.
     *
     * Starts a "heartbeat", see {@link timeoutHeartBeat()}.
     *
     * @access     private
     * @see        createDpLiving(), timeoutHeartBeat()
     */
    final function createDpObject()
    {
        $this->isLiving = new_dp_property(TRUE);
        $this->displayMode = new_dp_property('graphical');
        $this->sessionAge = new_dp_property(0, FALSE);
        $this->inputMode = new_dp_property('cmd');
        $this->isDraggable = FALSE;

        if (WEIGHT_TYPE_NONE !== WEIGHT_TYPE) {
            $this->weightCarry = new_dp_property(0, FALSE);

            $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            if (WEIGHT_TYPE_ABSTRACT === WEIGHT_TYPE) {
                $this->weight = 7;
                $this->maxWeightCarry = new_dp_property(7);
            } elseif (WEIGHT_TYPE_METRIC === WEIGHT_TYPE) {
                $this->weight = 70000; /* Grams */
                $this->maxWeightCarry = new_dp_property(30000);
            } elseif (WEIGHT_TYPE_USA === WEIGHT_TYPE) {
                $this->weight = 2458; /* Ounces */
                $this->maxWeightCarry = new_dp_property(1054);
            }
        }

        if (VOLUME_TYPE_NONE !== VOLUME_TYPE) {
            $this->volumeCarry = new_dp_property(0, FALSE);

            $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            if (VOLUME_TYPE_ABSTRACT === VOLUME_TYPE) {
                $this->volume = 7;
                $this->maxVolumeCarry = new_dp_property(5);
            } elseif (VOLUME_TYPE_METRIC === VOLUME_TYPE) {
                $this->volume = 70000;
                $this->maxVolumeCarry = new_dp_property(30000);
            } elseif (VOLUME_TYPE_USA === VOLUME_TYPE) {
                $this->volume = 2458;
                $this->maxVolumeCarry = new_dp_property(1054);
            }
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
         * @example /websites/dutchpipe.org/dpuniverse/obj/note.php A readable note
         * @see     getActionFailure(), setActionDefaultFailure(),
         *          getActionDefaultFailure()
         */
        $this->actionFailure = new_dp_property(DPUNIVERSE_ACTION_DEFAULT_FAILURE);


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
        $this->actionDefaultFailure = new_dp_property(DPUNIVERSE_ACTION_DEFAULT_FAILURE);

        $this->setBody(dptext("This description hasn't been set yet.<br />"));

        /* Actions for both NPCs and users */
        $this->addAction(dptext('take'), explode('#', dptext('take#get')), 'actionTake', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('drop'), dptext('drop'), 'actionDrop', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_OBJINV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('inventory'), explode('#', dptext('inventory#inv#i')), 'actionInventory', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('examine'), explode('#', dptext('examine#exam#exa#x#look#l')), 'actionExamine', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF | DP_ACTION_TARGET_LIVING | DP_ACTION_TARGET_OBJINV | DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        //$this->addAction(array($this, 'getMenuGiveLabel'), dptext('give'), 'actionGive', DP_ACTION_OPERANT_METHOD_MENU, DP_ACTION_TARGET_OBJINV, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('give to...'), dptext('give'), 'actionGive', DP_ACTION_OPERANT_METHOD_MENU, DP_ACTION_TARGET_OBJINV, array($this, 'getMenuGiveAuth'), DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('<div style="width: 81px; margin: 0px"><div style="float: left">page chat</div> <div style="float: right">TAB</div></div>'), dptext('say'), 'actionSay', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        $this->addAction(array(dptext('more chat'), dptext('emotions'), dptext('smile')), dptext('smile'), 'actionSmile', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dptext('more chat'), dptext('emotions'), dptext('grin')), dptext('grin'), 'actionGrin', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dptext('more chat'), dptext('emotions'), dptext('laugh')), dptext('laugh'), 'actionLaugh', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array($this, 'getEmotionsMenu'), dptext('cheer'), 'actionCheer', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF | DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dptext('more chat'), dptext('emotions'), dptext('nod')), dptext('nod'), 'actionNod', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dptext('more chat'), dptext('emotions'), dptext('shrug')), dptext('shrug'), 'actionShrug', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array($this, 'getEmotionsMenu'), dptext('pat'), 'actionPat', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array($this, 'getEmotionsMenu'), dptext('high5'), 'actionHighFive', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array($this, 'getEmotionsMenu'), dptext('hug'), 'actionHug', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array($this, 'getEmotionsMenu'), dptext('kiss'), 'actionKiss', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array($this, 'getEmotionsMenu'), dptext('dance'), 'actionDance', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dptext('more chat'), dptext('emotions'), dptext('emote...')), dptext('emote'), 'actionEmote', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        $this->addAction(array(dptext('more chat'), dptext('send message to...')), dptext('tell'), 'actionTell', DP_ACTION_OPERANT_METHOD_MENU, DP_ACTION_TARGET_SELF, array($this, 'getMenuTellAuth'), DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('send message...'), dptext('tell'), 'actionTell', array($this, "getTellOperant"), DP_ACTION_TARGET_USER, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dptext('more chat'), dptext('shout to site...')), dptext('shout'), 'actionShout', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

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
     * Reports an event
     *
     * Called when certain events occur, given with $name.
     *
     * Calls the method 'eventDpLiving' in this living object.
     *
     * @param      object    $name       Name of event
     * @param      mixed     $args       One or more arguments, depends on event
     * @since      DutchPIPE 0.2.0
     */
    final function eventDpObject($name)
    {
        $args = func_get_args();
        call_user_func_array(array($this, 'eventDpLiving'), $args);
    }

    /**
     * Reports an event
     *
     * Called when certain events occur, given with $name.
     *
     * @param      object    $name       Name of event
     * @param      mixed     $args       One or more arguments, depends on event
     * @since      DutchPIPE 0.2.0
     */
    function eventDpLiving($name)
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
     * Gets the livings's age since it was created as an object in a string
     *
     * Returns the livings's age in a format like "6 hours and 14 minutes".
     *
     * @return  string  livings's age
     * @see     DpUser::getInactive(), DpUser::isInactive(), DpUser::getStatus()
     */
    function getSessionAge()
    {
        return get_age2string(time() - $this->creationTime);
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
     * Tries to perform the action given by the living object
     *
     * Called by the system to handle both actions performed by clicking on
     * menus and actions from the input area. The first method will result in an
     * $action parameter such as "take object_242" (using an unique object id).
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
     *          DpObject::getActionData(), DpObject::getActionsMenu(),
     *          DpObject::getTargettedActions(),
     *          DpObject::performActionSubject()
     */
    public function performAction($action)
    {
        global $grCurrentDpObject;

        $this->lastActionTime = !isset($this->lastActionTime)
            ? new_dp_property(time()) : time();

        $action = trim($action);
        $grCurrentDpObject = $this;

        echo sprintf(dptext("Action by %s: %s\n"), $this->getTitle(), $action);

        $rval = (bool)$this->performActionSubject($action, $this);
        if (TRUE !== $rval) {
            if (strlen($action) && $action !== dptext('look')
                    && $action !== dptext('l')) {
                $action_failure = $this->getActionFailure();
                if (!isset($this->_GET['menuaction'])) {
                    $this->tell($action_failure);
                    if ($this->isUser && $this === get_current_dpuser()) {
                        get_current_dpuniverse()->setToldSomething();
                    }
                }
                $this->setActionFailure($this->getActionDefaultFailure());
            }
        }
        $grCurrentDpObject = FALSE;
        return $rval;
    }

    function getWeightCarry()
    {
        $inv = $this->getInventory();
        $weight = 0;

        foreach ($inv as &$ob) {
            if (isset($ob->weight)) {
                $weight += $ob->weight;
            }
        }

        return $weight;
    }

    function getVolumeCarry()
    {
        $inv = $this->getInventory();
        $volume = 0;

        foreach ($inv as &$ob) {
            if (isset($ob->volume)) {
                $volume += $ob->volume;
            }
        }

        return $volume;
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

        if (!($ob = $this->isPresent($noun))
                && !($description = $this->getItemDescription($noun))) {
            if (!($ob = $env->isPresent($noun))
                    && !($description = $env->getItemDescription($noun))) {
                $this->setActionFailure(sprintf(
                    dptext('There is no %s here.<br />'), $noun));
                return FALSE;
            }
        }

        if ($ob) {
            $description = $ob->getAppearance(0, TRUE, NULL, $this->displayMode,
                FALSE, 'dpexamine');
        }

        $this->tell("<window>$description</window>");
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
                if (!isset($ob->isLiving) || TRUE !== $ob->isLiving) {
                    $result = $ob->moveDpObject($this);
                    $picked_up = TRUE;
                    if (TRUE !== $result) {
                        if (E_MOVEOBJECT_HEAVY === $result) {
                            $this->tell(dptext("You can't carry more weight, drop something first.<br />"));
                        }
                        if (E_MOVEOBJECT_VOLUME === $result) {
                            $this->tell(dptext("You can't carry more volume, drop something first.<br />"));
                        }
                        continue;
                    }
                    $this->tell(dptext('You take %s.<br />',
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

        if (isset($ob->isLiving) && TRUE === $ob->isLiving) {
            $this->tell(ucfirst(sprintf(dptext('%s refuses to be taken.<br />'),
                $ob->getTitle())));
            return TRUE;
        }

        if ($ob->isHeap && preg_match("/^(\d+) /", $noun, $matches)
                && $matches[1] > 0 && $matches[1] < $ob->amount) {
            $result = $ob->moveDpObject($this, FALSE, $matches[1]);
            $title_definite = $title_indefinite = $noun;
        } else {
            $result = $ob->moveDpObject($this);
            $title_definite = $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE);
            $title_indefinite = $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE);
        }

        if (TRUE !== $result) {
            if (E_MOVEOBJECT_HEAVY === $result) {
                $this->tell(ucfirst(
                    dptext("You can't carry more weight, drop something first.<br />")));
            }
            if (E_MOVEOBJECT_VOLUME === $result) {
                $this->tell(ucfirst(
                    dptext("You can't carry more volume, drop something first.<br />")));
            }
            return TRUE;
        }
        $this->tell(sprintf(dptext('You take %s.<br />'),
            $title_definite));
        $env->tell(ucfirst(sprintf(dptext('%s takes %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $title_indefinite)),
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

        if ($ob->isHeap && preg_match("/^(\d+) /", $noun, $matches)
                && $matches[1] > 0 && $matches[1] < $ob->amount) {
            $ob->moveDpObject($env, FALSE, $matches[1]);
            $title_definite = $title_indefinite = $noun;
        } else {
            $ob->moveDpObject($env);
            $title_definite = $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE);
            $title_indefinite = $ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE);
        }
        if (FALSE === $silently) {
            $this->tell(sprintf(dptext('You drop %s.<br />'), $title_definite));
        }

        $env->tell(ucfirst(sprintf(dptext('%s drops %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $title_indefinite)), $this);
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
        $inventory = $this->getAppearance(0, TRUE, NULL,
            $this->displayMode, -1, 'dpobinv');
        /* :KLUDGE: */
        $carrying_str = dptext('You are carrying:');
        $inventory = str_replace($carrying_str, "<b>$carrying_str</b>",
            $inventory);
        $this->tell("<window name=\"inventory\">$inventory</window>");
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
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE), $noun)), $this);
        $this->tell(sprintf(dptext('You say: %s<br />'), $noun));
        return TRUE;
    }

    function getEmotionsMenu($verb, &$defined_by, &$target, &$performer)
    {
        //echo "$verb: THIS: {$this->title} DEFINED: {$defined_by->title}; TARGET: " . $target->title . "; PERFORMER: " . $performer->title . "\n";
        return $defined_by === $performer
            ? array(dptext('more chat'), dptext('emotions'), $verb)
            : array(dptext('emotions'), $verb);
    }

    function getTellOperant($verb, &$defined_by, &$target, &$performer)
    {
        return $defined_by->title;
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
    function getActionOperantMenu($verb, &$menuobj)
    {
        $user = get_current_dpuser();

        if ($verb === dptext('give')) {
            $ob_title = strtolower($menuobj->getTitle());
            $rval = dptext('give %s to %s');
            if (FALSE !== ($env = $this->getEnvironment())) {
                $menu = array();
                $inv = $env->getInventory();
                foreach ($inv as &$ob) {
                    if ($ob->isLiving && $ob !== $user) {
                        $to_title = strtolower($ob->getTitle());
                        $menu[$to_title] = sprintf($rval, $ob_title, $to_title);
                    }
                }
            }
            return !count($menu) ? FALSE : $menu;
        } elseif ($verb === dptext('tell')) {
            $rval = dptext('tell %s');
            $users = get_current_dpuniverse()->getUsers();
            if (sizeof($users)) {
                $menu = array();
                foreach ($users as &$u) {
                    if ($u !== $user) {
                        $to_title = strtolower($u->getTitle());
                        $menu[$to_title] = sprintf($rval, $to_title);
                    }
                }
            }
            return !count($menu) ? FALSE : $menu;
        }
        return FALSE;
    }

    function getMenuGiveAuth()
    {
        if (FALSE !== ($env = $this->getEnvironment())) {
            $inv = $env->getInventory();
            foreach ($inv as &$ob) {
                if ($ob->isLiving && $ob !== get_current_dpuser()) {
                    return DP_ACTION_AUTHORIZED_ALL;
                }
            }
        }

        return DP_ACTION_AUTHORIZED_DISABLED;
    }

    function getMenuTellAuth()
    {
        if (1 < get_current_dpuniverse()->getNrOfUsers()) {
            return DP_ACTION_AUTHORIZED_ALL;
        }

        return DP_ACTION_AUTHORIZED_DISABLED;
    }

    function getMenuGiveLabel()
    {
        if (FALSE !== ($env = $this->getEnvironment())) {
            $inv = $env->getInventory();
            foreach ($inv as &$ob) {
                if ($ob->isLiving && $ob !== get_current_dpuser()) {
                    return dptext('give...');
                }
            }
        }

        return dptext("<span style='color: #999'>give to...</span>");
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
            $this->tell(sprintf(dptext('You have no %s.<br />'), $what));
            return TRUE;
        }
        $env = $this->getEnvironment();
        if (!is_object($env) || !($who_ob = $env->isPresent($who))) {
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
     * Makes this living object tell something to another user object
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

        if (FALSE === ($pos = strpos($noun, ' '))) {
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
        if (is_null($noun)) {
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
     * Makes this living object cheer wildly
     *
     * @param   string  $verb       the action, "cheer"
     * @param   string  $noun       who to cheer on, could be empty
     * @return  boolean TRUE
     */
    function actionCheer($verb, $noun)
    {
        if (!strlen($noun) || $this->isId($noun)) {
            $this->tell(dptext('You jump up and down cheering.<br />'));
            if (FALSE !== ($env = $this->getEnvironment())) {
                $env->tell(ucfirst(sprintf(
                    dptext('%s jumps up and down cheering.<br />'),
                    $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))), $this);
            }
            return TRUE;
        }

        if (FALSE === ($env = $this->getEnvironment()) ||
                ($noun && !($dest_ob = $env->isPresent($noun)))) {
            $this->setActionFailure(sprintf(dptext("Couldn't find: %s<br />"),
                $noun));
            return FALSE;
        }
        if (!isset($dest_ob)) {
            $this->setActionFailure(dptext('Cheer on who?<br />'));
            return FALSE;
        }
        $this->tell(sprintf(
            dptext('You jump up and down cheering on %s.<br />'),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dptext('%s jumps up and down cheering on %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $dest_ob);
        $dest_ob->tell(ucfirst(sprintf(
            dptext('%s jumps up and down cheering you on.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        return TRUE;
    }

    /**
     * Makes this living object nod solemnly
     *
     * @param   string  $verb       the action, "nod"
     * @param   string  $noun       empty string
     * @return  boolean TRUE
     */
    function actionNod($verb, $noun)
    {
        $this->tell(dptext('You nod solemnly.<br />'));
        if (FALSE !== ($env = $this->getEnvironment())) {
            $env->tell(ucfirst(sprintf(dptext('%s nods solemnly.<br />'),
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
}
?>
