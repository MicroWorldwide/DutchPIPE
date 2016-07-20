<?php
/**
 * A user object, the object representing a real user
 *
 * DutchPIPE version 0.4; PHP version 5
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
 * @version    Subversion: $Id: DpUser.php 286 2007-08-21 11:24:10Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpLiving
 */

/**
 * Builts upon the standard DpLiving class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpLiving.php');

/**
 * Gets title type constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'title_types.php');

/**
 * A user object, the object representing a real user
 *
 * Currently the only differerence with a NPC (which shares the same DpLiving
 * class) is the passing of the user's last HTTP request's server, request and
 * cookie variables, and the tell method (NPCs don't have a browser).
 * See the DpLiving class for most functionality.
 *
 * Creates the following DutchPIPE properties:<br />
 *
 * - boolean <b>isUser</b> - Set to TRUE
 * - boolean <b>isRegistered</b> - TRUE if this is a registered user, FALSE
 *   otherwise
 * - boolean <b>isAdmin</b> - TRUE if this user is an administrator, FALSE
 *   otherwise
 * - boolean <b>noCookies</b> - TRUE if the user's browser didn't accept our
 *   cookie
 * - boolean <b>isKnownBot</b> - TRUE if this is a known search engine bot,
 *   FALSE otherwise
 * - boolean <b>isAjaxCapable</b> - TRUE if the user's browser is AJAX-capable,
 *   FALSE otherwise
 * - string <b>age</b> - Descriptive age of the user
 * - string <b>inactive</b> - Descriptive inactive time of the user, empty
 *   string for active
 * - boolean <b>isInactive</b> - TRUE if the user in inactive, FALSE otherwise
 * - integer <b>avatarNr</b> - Avatar image number
 * - mixed <b>status</b> - FALSE for no special status, otherwise a descriptive
 *   string, 'away'
 * - string <b>inputMode</b> - Input field mode, "say" or "cmd"
 * - string <b>inputEnabled</b> - Is input field visibile? Either "on" or "off"
 * - string <b>inputPersistent</b> - Input field options
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpUser extends DpLiving
{
    /**
     * Variables set by the web server of related to dpclient.php's environment
     *
     * This environment is the execution environment of the current dpclient.php
     * script.
     *
     * @var         array
     */
    public $_SERVER;

    /**
     * Variables which are currently registered to a script's session
     *
     * @var         array
     */
    public $_SESSION;

    /**
     * Variables provided via HTTP cookies
     *
     * @var         array
     */
    public $_COOKIE;

    /**
     * Variables provided via URL query string
     *
     * @var         array
     */
    public $_GET;

    /**
     * Variables provided via HTTP POST
     *
     * @var         array
     */
    public $_POST;

    /**
     * Variables provided via HTTP post file uploads
     *
     * @var         array
     */
    public $_FILES;

    /**
     * Counter for AJAX events, increased with each event
     *
     * @var         string
     */
    private $mEventCount = 0;

    /**
     * Status of last connection, for example 'busy', used to detect change
     *
     * @var         string
     */
    private $mLastStatus = FALSE;

    /**
     * Events this user will be alerted of, such as people entering the site
     *
     * @var         array
     */
    public $mAlertEvents = array();

    /**
     * History of input field actions, can be accesed by up and down arrow keys
     *
     * @var         array
     */
    public $mActionHistory = array();

    /**
     * Initializes the user
     */
    function createDpLiving()
    {
        $this->addId(dp_text('user'));
        $this->isUser = new_dp_property(TRUE);
        $this->isRegistered = new_dp_property(FALSE);
        $this->isAdmin = new_dp_property(FALSE);
        $this->noCookies = new_dp_property(FALSE);
        $this->isKnownBot = new_dp_property(FALSE);
        $this->isAjaxCapable = new_dp_property(FALSE);
        $this->age = new_dp_property(0, FALSE);
        $this->inactive = new_dp_property(FALSE, FALSE, 'getInactive');
        $this->isInactive = new_dp_property(FALSE, FALSE, 'isInactive');
        $this->inputMode = 'say';
        $this->inputEnabled = new_dp_property('off');
        $this->inputPersistent = new_dp_property('page');

        $avatar_nr = $this->_getRandAvatarNr();
        $this->avatarNr = new_dp_property($avatar_nr);
        $this->setTitle(dp_text('User'));
        $this->titleType = DPUNIVERSE_TITLE_TYPE_NAME;
        $this->titleImg = DPUNIVERSE_AVATAR_URL . 'user' . $avatar_nr . '.gif';
        $this->status = new_dp_property(FALSE, NULL, 'getStatus');
        $this->setBody('<img src="' . DPUNIVERSE_AVATAR_URL . 'user'
            . $avatar_nr . '_body.gif" border="0" alt="" align="left" '
            . 'style="margin-right: 15px" />' . dp_text('A user.') . '<br />');

        /* Actions for everybody */
        $this->addAction(dp_text("who's here?"), dp_text('who'), 'actionWho', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('avatar & other settings')), explode('#', dp_text('settings#config')), 'actionSettings', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('my home')), dp_text('myhome'), 'actionMyhome', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_REGISTERED, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('set my home')), dp_text('myhome set'), 'actionMyhome', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_REGISTERED, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('login/register')), dp_text('login'), 'actionLoginOut', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_GUEST, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('logout')), dp_text('logout'), 'actionLoginOut', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_REGISTERED, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('advanced'), array($this, 'getModeChecked')), dp_text('mode'), 'actionMode', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('advanced'), dp_text('show page links')), dp_text('links'), 'actionLinks', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('tools'), dp_text('advanced'), dp_text('show source')), dp_text('source'), 'actionSource', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        /* Actions for admin only */
        $this->addAction(array(dp_text('admin'), dp_text('goto...')), dp_text('goto'), 'actionGoto', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('admin'), dp_text('reset')), dp_text('reset'), 'actionReset', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('admin'), dp_text('svars')), dp_text('svars'), 'actionSvars', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF | DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('admin'), dp_text('force...')), dp_text('force'), 'actionForce', array($this, 'actionMoveOperant'), DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('admin'), dp_text('move...')), dp_text('move'), 'actionMove', array($this, 'actionMoveOperant'), DP_ACTION_TARGET_SELF | DP_ACTION_TARGET_LIVING | DP_ACTION_TARGET_OBJINV | DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('admin'), dp_text('destroy')), dp_text('destroy'), 'actionDestroy', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING | DP_ACTION_TARGET_OBJINV | DP_ACTION_TARGET_OBJENV, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(array(dp_text('admin'), dp_text('object list')), dp_text('oblist'), 'actionOblist', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);

        /* Last action in menu */
        $this->addAction(dp_text('help'), dp_text('help'), 'actionHelp', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        $this->addValidClientCall('setSettings');
    }

    /**
     * Gets a random avatar image number in order to give guests an avatar
     *
     * Checks the avatar image directory for images with the format:
     * public/avatar/user<number>.gif
     * for possible numbers.
     *
     * @return  int     random avatar image number
     * @see     _getNrOfAvatars
     */
    private function _getRandAvatarNr()
    {
        $entries = $this->_getNrOfAvatars();
        if (0 === $entries) {
            return 1;
        }
        return (mt_rand(51, 50 + $entries) - 50);
    }

    /**
     * Gets the user's age in a string
     *
     * Returns the user's age in a format like "1 day, 6 hours and 14 minutes".
     *
     * @return  string  user's age
     * @see     getInactive, isInactive, getStatus, DpLiving::getSessionAge()
     */
    protected function getAge()
    {
        if (!$this->isRegistered) {
            return FALSE;
        }

        $result = dp_db_query('SELECT userAge FROM Users WHERE '
            . 'userUsernameLower='
            . dp_db_quote(dp_strtolower($this->getTitle()), 'text'));

        $age = !$result || !dp_db_num_rows($result)
            ? 0 : dp_db_fetch_one($result, 0, 0);

        dp_db_free($result);

        return get_age2string($age + (time() - $this->creationTime));

    }

    /**
     * Gets inactive time of the user in a string
     *
     * Returns how long the user is inactive in a format like "2 hours and 14
     * minutes".
     *
     * @return  string  user's inactivity
     * @see     getAge, isInactive, getStatus, DpLiving::getSessionAge()
     */
    function getInactive()
    {
        $inactive_time = time() - (!isset($this->lastActionTime)
            ? $this->creationTime : $this->lastActionTime);

        if ($inactive_time < 300) {
            return '';
        }

        return get_age2string($inactive_time);
    }

    /**
     * Is this user inactive?
     *
     * Inactive is defined as a user who hasn't shown activity in the last 5
     * minutes.
     *
     * @return  status  TRUE if the user is inactive, FALSE if active
     * @see     getAge, getInactive, getStatus, DpLiving::getSessionAge()
     */
    function isInactive()
    {
        $inactive_time = time() - (!isset($this->lastActionTime)
            ? $this->creationTime : $this->lastActionTime);

        return $inactive_time >= 300;
    }

    /**
     * Gets a descriptive user status string
     *
     * If the user has a special status like inactive, returns a string like
     * "away", to use in user titles and such. If there is no special status,
     * returns an empty string.
     *
     * @return  string  status description
     * @see     getAge, getInactive, isInactive, DpLiving::getSessionAge()
     */
    function getStatus()
    {
        if ($this->isInactive) {
            return dp_text('away');
        }

        return FALSE;
    }

    /**
     * Calls itself every "heartbeat"
     *
     * Redefine this method to make timed stuff happen.
     */
    function timeoutHeartBeat()
    {
        DpLiving::timeoutHeartBeat();

        if ($this->status === $this->mLastStatus) {
            return;
        }

        $this->mLastStatus = $this->status;

        $this->tell(array('abstract' => '<changeDpElement id="'
            . $this->getUniqueId() . '"><b>'
            . $this->getAppearance(1, FALSE) . '</b></changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $this->getUniqueId() . '"><b>'
            . $this->getAppearance(1, FALSE, $this, 'graphical')
            . '</b></changeDpElement>'));
        $this->getEnvironment()->tell(array(
            'abstract' => '<changeDpElement id="' . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE, $this, 'graphical')
            . '</changeDpElement>'), $this);
    }

    /**
     * Sets various PHP global variables passed on from the DutchPIPE server
     *
     * Called each time a user's browser does a normal page or AJAX request.
     * Several variables are passed which represent their corresponding PHP
     * global arrays: $_SERVER, $_COOKIE, etc.
     *
     * @param   array   &$rServerVars  User server variables
     * @param   array   &$rSessionVars User session variables
     * @param   array   &$rCookieVars  User cookie variables
     * @param   array   &$rGetVars     User get variables
     * @param   array   &$rPostVars    User post variables
     * @param   array   &$rFilesVars   User files variables
     */
    function setVars(&$rServerVars, &$rSessionVars, &$rCookieVars, &$rGetVars,
            &$rPostVars, &$rFilesVars)
    {
        $this->_SERVER = $rServerVars;
        $this->_SESSION = $rSessionVars;
        $this->_COOKIE = $rCookieVars;
        $this->_GET = $rGetVars;
        $this->_POST = $rPostVars;
        $this->_FILES = $rFilesVars;
    }

    /**
     * Sends something to dpclient-js.php running on the user's browser
     *
     * Sends the given data to the user's browser. It's up the user's DutchPIPE
     * Javascript client, dpclient-js.php by default, what to do with the
     * received content. dpclient-js.php can be send data like
     * '<message>hello world</message>' and '<window>hello world</window>',
     * to show something in the message area and pop up a window respectively.
     *
     * Can be binded to an environment. This is useful for locations producing
     * messages: the user could be moving to another location before the client
     * catches the new message with AJAX, in which case the user should not get
     * the message.
     *
     * @param      string    $data         message string
     * @param      object    &$binded_env  optional binded environment
     */
    function tell($data, &$binded_env = NULL)
    {
        if ((FALSE === is_string($data) || 0 === dp_strlen($data)) &&
                (FALSE === is_array($data)) || 0 === count($data)) {
            return;
        }

        if (FALSE === is_null($binded_env)
                && (FALSE === ($env = $this->getEnvironment())
                || $env !== $binded_env)) {
            echo dp_text("Message skipped, no longer in page\n");
            return;
        }

        // If this user is the same user doing the current HTTP request, tell
        // straight away. Otherwise, store the message for the next time we get
        // a HTTP request from the user.
        if (TRUE === get_current_dpuniverse()->isNoDirectTell()
                || $this !== get_current_dpuser()) {
            //echo sprintf(dp_text("Storing %s: %s\n"), $this->getTitle(),
            //    (dp_strlen($data) > 512 ? dp_substr($data, 0, 512) : $data));
            get_current_dpuniverse()->storeTell($this, $data, $binded_env);
            return;
        }
        if (is_array($data)) {
            $data = $data[$this->displayMode];
        }

        // Gets the message type from $data which holds a string with the format
        // <type>tellstuff</type>. If no type was given in the $data string, we
        // consider it type 'message':
        list($mtype_start, $data, $mtype_end) = $this->_tellParseTag($data);

        $this->_tellDoTell($mtype_start, $data, $mtype_end);
    }

    private function _tellParseTag($data)
    {
         if (dp_strlen($data) >=3 && dp_substr($data, 0, 1) == '<'
                && FALSE !== ($pos = dp_strpos($data, '>'))) {
            $mtype_start = dp_substr($data, 1, $pos - 1);
            $endpos = dp_strrpos($data, '<');
            $mtype_end = dp_substr($data, $endpos + 2, -1);

            $data = "<![CDATA[" . dp_substr($data, dp_strlen($mtype_start) + 2,
                $endpos - dp_strlen($mtype_start) - 2) . ']]>';
        } else {
            $mtype_start = $mtype_end = 'message';
            $data = "<![CDATA[$data]]>";
        }

        return array($mtype_start, $data, $mtype_end);
    }

    private function _tellDoTell($mtype_start, $data, $mtype_end)
    {
        $universe = get_current_dpuniverse();

        if (isset($this->_GET['ajax'])) {
            if ($mtype_end === 'message' && $data === '<![CDATA[empty]]>') {
                $mtype_start = $mtype_end = '';
                $data = '1';
            }
        }
        if ($data !== '1' || !$universe->isToldSomething()) {
            if ($mtype_start !== '') {
                $data = "<$mtype_start>$data</$mtype_end>";
            }
            /*
            if ($data !== '1') {
                echo sprintf(dp_text("Telling %s: %s\n"), $this->getTitle(),
                    (dp_strlen($data) > 236 ? dp_substr($data, 0, 236)
                    : $data));
            }
            */
            if ($mtype_end === '' || $mtype_end === 'header'
                    || $mtype_end === 'cookie' || $mtype_end === 'location') {
                $universe->tellCurrentDpUserRequest($data);
            } else {
                $universe->tellCurrentDpUserRequest('<event count="'
                    . $this->mEventCount++ . '" time="' . time() . '">' . $data
                    . '</event>');
            }
        }
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
        if (EVENT_DESTROYING_OBJ !== $name || !isset($this->isRegistered)
                || TRUE !== $this->isRegistered) {
            return;
        }

        $result = dp_db_query('SELECT userAge FROM Users WHERE '
            . 'userUsernameLower='
            . dp_db_quote(dp_strtolower($this->getTitle()), 'text'));
        $age = !$result || !dp_db_num_rows($result) ? 0
            : dp_db_fetch_one($result, 0, 0);
        dp_db_free($result);

        $new_age = $age + (time() - $this->creationTime);
        dp_db_exec('UPDATE Users set userAge=' . dp_db_quote($new_age)
            . ' WHERE userUsernameLower='
            . dp_db_quote(dp_strtolower($this->getTitle()), 'text'));
    }

    /**
     * Experimental, ignore.
     *
     * @access     private
     * @since      DutchPIPE 0.2.0
     */

    function isDraggable(&$by_who)
    {
        if ($by_who === $this) {
            return TRUE;
        }

        return DpLiving::isDraggable($by_who);
    }

    /**
     * Tries to perform the action given by the user object
     *
     * Handles input area input mode and history. Uses DpLiving::performAction()
     * for the real stuff. See DpLiving::performAction() for more information.
     *
     * @param   string  $action     the action given by the user object
     * @return  boolean TRUE for success, FALSE for unsuccessful action
     * @see     DpLiving::performAction()
     */
    final public function performAction($action)
    {
        $action = $orig_action = trim($action);
        if (!isset($this->_GET['menuaction']) && 'say' === $this->inputMode
                && dp_strlen($action)) {
            if (dp_strlen($action) && '/' === dp_substr($action, 0, 1)) {
                $action = $orig_action = dp_substr($action, 1);
            } else {
                $action = sprintf(dp_text('say %s'), $action);
            }
        }
        $rval = DpLiving::performAction($action);

        if ('' !== $action && isset($this->_GET['cmdline'])
             && (!($sz = count($this->mActionHistory))
             || $this->mActionHistory[$sz - 1] !== $action)) {
            $this->mActionHistory[] = $orig_action;
            if (count($this->mActionHistory) > 20) {
                array_shift($this->mActionHistory);
            }
        }

        return $rval;
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
        $tmp = dp_file_get_contents(DPUNIVERSE_STD_PATH
            . ('say' === $this->inputMode ? 'help.html' : 'commands.html'));

        $this->tell("<window><div id=\"helptext\">" . $tmp
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
        if (!dp_strlen($noun)) {
            $what = $this->getEnvironment();
        } else {
            if (FALSE === ($what = $this->isPresent($noun))
                    && FALSE === ($what =
                    $this->getEnvironment()->isPresent($noun))) {
                $this->tell(sprintf(dp_text("Can't find: %s<br />"), $noun));
                return TRUE;
            }
        }

        if (FALSE === ($what = $this->getEnvironment())) {
            return FALSE;
        }
        /* Without the \n and &nbsp; to &#160 conversion, highlight_file gave
         invalid XHTML */
        $this->tell("<window styleclass=\"dpwindow_src\">\n"
            . str_replace('&nbsp;', '&#160;', @highlight_file(DPUNIVERSE_PATH
            . $what->location, TRUE) . "\n</window>"));
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
        if (!dp_strlen($noun)) {
            $what = $this->getEnvironment();
        } else {
            if (FALSE === ($what = $this->isPresent($noun))
                    && FALSE === ($what =
                    $this->getEnvironment()->isPresent($noun))) {
                $this->tell(sprintf(dp_text("Can't find: %s<br />"), $noun));
                return TRUE;
            }
        }

        if (FALSE === method_exists($what, 'getExits')
                || 0 === count($links = $what->getExits())) {
            $tell = '<b>' . sprintf(dp_text('No links found in %s'),
                $what->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
                . '</b><br />';
        } else {
            $tell = '<b>' . sprintf(dp_text('Links found in: %s'),
                $what->getTitle()) . '</b><br /><br />';
            foreach ($links as $linktitle => $linkdata) {
                if ($linktitle === DPUNIVERSE_NAVLOGO) {
                    $linkcommand = dp_text('home');
                } else {
                    $linkcommand = explode(' ', $linktitle);
                    $linkcommand = dp_strtolower($linktitle);
                }
                $tell .= "<a href=\"" . DPSERVER_CLIENT_URL
                . "?location={$linkdata[0]}\">$linkcommand</a><br />";
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
           $this->tell('<window><b>' . dp_text('No one is on this site.')
            . '</b></window>');
            return TRUE;
        }

        $tell = '<b>' . dp_text('People currently on this site:')
            . '</b><br />';
        $tell .= '<table cellpadding="0" cellspacing="0" border="0" style="'
            . 'margin-top: 5px">';
        foreach ($users as &$user) {
            $env = $user->getEnvironment();
            $loc = $env->location;
            if (0 !== dp_strpos($loc, 'http://')) {
                $loc = DPSERVER_CLIENT_URL . '?location=' . $loc;
            }
            $env = FALSE === $env ? '-' : '<a href="' . $loc . '">'
                . $env->getTitle() . '</a>';

            $status = !isset($user->status) || FALSE === $user->status ? ''
                : ' (' . $user->status . ')';

            $tell .= '<tr><td>' . $user->getTitle() . $status
                . '</td><td style="padding-left: 10px">' . $env . '</td></tr>';
        }
        $tell .= '</table>';
        $this->tell("<window>$tell</window>");
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
function send_settings()
{
    var avatar_nr = 1;
    for (i=1;_gel("avatar_nr"+i) != undefined;i++) {
        if (_gel("avatar_nr"+i).checked) {
            avatar_nr = i;
            break;
        }
    }

    jQuery.ajax({
        url: "' . DPSERVER_CLIENT_URL . '",
        data: "location='
            . $this->getEnvironment()->location
            . '&rand="+Math.round(Math.random()*9999)
            + "&call_object="+escape("' . $this->getUniqueId() . '")
            + "&method=setSettings"
            + "&avatar_nr="+avatar_nr
            + "&display_mode="+(_gel("display_mode1").checked
                ? _gel("display_mode1").value : _gel("display_mode2").value)
            + "&people_entering="+(_gel("people_entering").checked ? "1" : "0")
            + "&people_leaving="+(_gel("people_leaving").checked ? "1" : "0")
            + "&bots_entering="+(_gel("bots_entering").checked ? "1" : "0"),
        success: handle_response
    });

    return false;
}
</script>');
        $nr_of_avatars = $this->_getNrOfAvatars();
        $cur_avatar_nr = (int)$this->avatarNr;
        for ($avatar_settings = '', $i = 1; $i <= $nr_of_avatars; $i++) {
            $avatar_settings .= '<input type="radio" id="avatar_nr' . $i
                . '" name="avatar_nr" value="' . $i . '"'
                . ($cur_avatar_nr !== $i ? '' : ' checked="checked"')
                . ' onClick="send_settings()" style="cursor: pointer" />' . $i
                . "&#160; ";
        }

        $this->tell('<window>' . dp_text('Choose your avatar:') . '<br />'
. $avatar_settings . '<br /><br />'
. dp_text('People and items on the page are displayed in:') . '<br />
<input type="radio" id="display_mode1" name="display_mode" value="graphical"' . ($this->displayMode == 'graphical' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dp_text('Graphical mode') . '<br />
<input type="radio" id="display_mode2" name="display_mode" value="abstract"' . ($this->displayMode == 'abstract' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dp_text('Abstract mode') . '<br /><br />'
. dp_text('Alert me of the following events:') . '<br />
<input type="checkbox" id="people_entering" name="people_entering" value="1"' . (isset($this->mAlertEvents['people_entering']) ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dp_text('People entering this site') . '<br />
<input type="checkbox" id="people_leaving" name="people_leaving" value="1"' . (isset($this->mAlertEvents['people_leaving']) ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dp_text('People leaving this site') . '<br />
<input type="checkbox" id="bots_entering" name="bots_entering" value="1"' . (isset($this->mAlertEvents['bots_entering']) ?  ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dp_text('Search engines indexing pages') . '<br />'
            . ('http://dutchpipe.org' !== DPSERVER_HOST_URL ? '' : '<br />'
. '<div id="box"><a href="http://www.messdudes.com/" target="_blank"><b>Mess Dudes</b></a> has kindly allowed DutchPIPE to use a number of avatars.</div>') . '
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
     */
    function setSettings()
    {
        if (!isset($this->_GET['avatar_nr'])
                || 0 === dp_strlen($avatar_nr = $this->_GET['avatar_nr'])
                || !isset($this->_GET['display_mode'])
                || 0 === dp_strlen($display_mode
                = $this->_GET['display_mode'])) {
            $this->tell(dp_text('Error receiving settings.<br />'));
        }

        $this->avatarNr = $avatar_nr;
        $this->setTitleImg(DPUNIVERSE_AVATAR_URL . 'user' . $avatar_nr
            . '.gif');
        $this->setBody('<img src="' . DPUNIVERSE_AVATAR_URL . 'user'
            . $avatar_nr . '_body.gif" border="0" alt="" align="left" '
            . 'style="margin-right: 15px" />' . dp_text('A user.') . '<br />');

        $this->displayMode = $display_mode;

        if (isset($this->_GET['people_entering']) && $this->_GET['people_entering'] == '1') {
            $this->mAlertEvents['people_entering'] = TRUE;
            get_current_dpuniverse()->addAlertEvent('people_entering', $this);
        } elseif (isset($this->mAlertEvents['people_entering'])) {
            unset($this->mAlertEvents['people_entering']);
            get_current_dpuniverse()->removeAlertEvent('people_entering', $this);
        }

        if (isset($this->_GET['people_leaving']) && $this->_GET['people_leaving'] == '1') {
            $this->mAlertEvents['people_leaving'] = TRUE;
            get_current_dpuniverse()->addAlertEvent('people_leaving', $this);
        } elseif (isset($this->mAlertEvents['people_leaving'])) {
            unset($this->mAlertEvents['people_leaving']);
            get_current_dpuniverse()->removeAlertEvent('people_leaving', $this);
        }

        if (isset($this->_GET['bots_entering']) && $this->_GET['bots_entering'] == '1') {
            $this->mAlertEvents['bots_entering'] = TRUE;
            get_current_dpuniverse()->addAlertEvent('bots_entering', $this);
        } elseif (isset($this->mAlertEvents['bots_entering'])) {
            unset($this->mAlertEvents['bots_entering']);
            get_current_dpuniverse()->removeAlertEvent('bots_entering', $this);
        }

        if ($this->isRegistered) {
            dp_db_exec("UPDATE Users set "
                . "userAvatarNr='" . addslashes($avatar_nr)
                . "',userDisplayMode='" . addslashes($display_mode)
                . "',userEventPeopleEntering='"
                . (!isset($this->mAlertEvents['people_entering']) ? '0' : '1')
                . "',userEventPeopleLeaving='"
                . (!isset($this->mAlertEvents['people_leaving']) ? '0' : '1')
                . "',userEventBotsEntering='"
                . (!isset($this->mAlertEvents['bots_entering']) ? '0' : '1')
                . "' WHERE userUsernameLower='"
                . addslashes(dp_strtolower($this->getTitle())) . "'");
        }

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
        if (!dp_strlen($noun)) {
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
                            dp_text('Target %s not found.<br />'), $noun));
                        return FALSE;
                    }
                }
            }
        }
        $this->tell('<window><b>' . sprintf(dp_text('Server variables of %s:'),
            $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . '</b><br /><pre>' . print_r($ob->_SERVER, TRUE) . '</pre>'
            . '<b>' . sprintf(dp_text('Properties of %s:'),
            $ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
            . '</b><pre>' . htmlentities(print_r($ob->getProperties(), TRUE))
            . '</pre></window>');
        return TRUE;
    }

    /**
     * Destroys an object
     *
     * @param   string  $verb       the action, "destroy"
     * @param   string  $noun       the object to destroy, for example "rose"
     * @return  boolean TRUE for action completed, FALSE otherwise
     * @since   DutchPIPE 0.4.0
     */
    function actionDestroy($verb, $noun)
    {
        if (FALSE === ($env = $this->getEnvironment()) ||
                ($noun && !($dest_ob = $env->isPresent($noun)))) {
            $this->setActionFailure(sprintf(dp_text("Couldn't find: %s<br />"),
                $noun));
            return FALSE;
        }
        if (!isset($dest_ob)) {
            $this->setActionFailure(dp_text('Destroy what?<br />'));
            return FALSE;
        }
        $this->tell(sprintf(
            dp_text('You destroy %s.<br />'),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dp_text('%s destroys %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $dest_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $dest_ob);
        $dest_ob->tell(ucfirst(sprintf(
            dp_text('%s destroys you.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));
        $dest_ob->removeDpObject();

        return TRUE;
    }

    /**
     * Shows a list of all objects in this DutchPIPE universe in a window
     *
     * @param   string  $verb       the action, "oblist"
     * @param   string  $noun       empty string
     * @return  boolean TRUE for action completed, FALSE otherwise
     * @see     DpUniverse::gteObjectList()
     * @since   DutchPIPE 0.4.0
     */
    function actionOblist($verb, $noun)
    {
        $this->tell('<stylesheet href="' . DPUNIVERSE_WWW_URL
            . 'oblist.css"></stylesheet>');
        $this->tell('<window styleclass="dpwindow_oblist" delay="100">'
            . '<div class="dpoblist_div">'
            . get_current_dpuniverse()->getObjectList()
            . '</div><a href="javascript:send_action2server(\'oblist\')">Reload'
            . '</a></window>');
        $this->tell('<script type="text/javascript" ' .
            'src="' . DPUNIVERSE_WWW_URL . 'sorttable.js"></script>');
        $this->tell("<script>\nsetTimeout('sorttable.init()', 200);\n"
            . "</script>");
        return TRUE;
    }

    /**
     * Go to a given location
     *
     * @param   string  $verb       the action, "goto"
     * @param   string  $noun       the location, for example "/page/about.php"
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionGoto($verb, $noun)
    {
        if (!dp_strlen($noun)) {
            $this->setActionFailure(dp_text('Goto where?<br />'));
            return FALSE;
        }

        $this->tell("<location>$noun</location>");
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
        if (!dp_strlen($noun = trim($noun))
                || FALSE === ($pos = dp_strpos($noun, ' '))) {
            $this->setActionFailure(
                dp_text('Syntax: force <i>who what</i>.<br />'));
            return FALSE;
        }

        $noun = str_replace('&quot;', '"', $noun);

        if (dp_substr($noun, 0, 1) == '"') {
            $noun = trim(dp_substr($noun, 1));
            if (FALSE !== ($pos2 = dp_strpos($noun, '"'))) {
                $who = dp_substr($noun, 0, $pos2);
                $what = dp_substr($noun, $pos2 + 1);
            }
        }
        if (!isset($who)) {
            $who = dp_substr($noun, 0, $pos);
            $what = dp_substr($noun, $pos + 1);
        }

        if (FALSE === ($who_ob = $this->isPresent($who))) {
            if (FALSE !== ($env = $this->getEnvironment())) {
                $who_ob = $env->isPresent($who);
            }
        }
        if (FALSE === $who_ob) {
            $this->setActionFailure(sprintf(
                dp_text('Target %s not found.<br />'), $who));
            return FALSE;
        }

        $this->tell(sprintf(
            dp_text('You give %s the old "Jedi mind-trick" stink eye.<br />'),
            $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $env->tell(ucfirst(sprintf(
            dp_text('%s gives %s the old "Jedi mind-trick" stink eye.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
            $who_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $this, $who_ob);
        $who_ob->tell(ucfirst(sprintf(
            dp_text('%s gives you the old "Jedi mind-trick" stink eye.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        $who_ob->performAction($what);
        return TRUE;
    }

    /**
     * Completes the move action performed by clicking on an object
     *
     * @param   string  $verb       the action, "move"
     * @return  string  a string such as "beer "
     * @see     actionMove()
     */
    function actionMoveOperant($verb, &$menuobj)
    {
        $title = dp_strtolower($menuobj->getTitle());

        return (FALSE === dp_strpos($title, ' ') ? $title : '"' . $title . '"')
            . ' ';
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
        if (!dp_strlen($noun = trim($noun))
                || FALSE === ($pos = dp_strpos($noun, ' '))) {
            $this->setActionFailure(
                dp_text('Syntax: move <i>what where</i>.<br />'));
            return FALSE;
        }

        $noun = str_replace('&quot;', '"', $noun);

        if (dp_substr($noun, 0, 1) == '"') {
            $noun = trim(dp_substr($noun, 1));
            if (FALSE !== ($pos2 = dp_strpos($noun, '"'))) {
                $what = dp_substr($noun, 0, $pos2);
            }
        }
        if (!isset($what)) {
            $what = dp_substr($noun, 0, $pos);
        }

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
                dp_text('Object to move %s not found.<br />'), $what));
            return FALSE;
        }

        $pos = !isset($pos2) ? $pos + 1 : $pos2 + 2;
        if (dp_strlen($where = trim(dp_substr($noun, $pos))))  {
            if (dp_substr($where, 0, 1) == '"') {
                $where = trim(dp_substr($where, 1));
                if ($pos = dp_strpos($where, '"')) {
                    $where = dp_substr($where, 0, $pos);
                }
            }
        }

        if (!dp_strlen($where))  {
            $this->setActionFailure(
                dp_text('Syntax: move <i>what where</i>.<br />'));
            return FALSE;
        }

        $env = $this->getEnvironment();
        if ('!' === $where) {
            $where_ob = $this->getEnvironment();
            if (FALSE === $where_ob) {
                $this->setActionFailure(sprintf(
                    dp_text("Can't move object %s to this location: you have no environment.<br />"),
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
                dp_text('Target %s not found.<br />'), $where));
            return FALSE;
        }

        if ($this === $what_ob && $this === $where_ob) {
            $this->setActionFailure(
                dp_text('You cannot move yourself into yourself.<br />'));
            return FALSE;
        }

        $this->tell(sprintf(
            dp_text('You give %s the old "Jedi mind-trick" stink eye.<br />'),
            $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        if (FALSE !== $env) {
            $env->tell(ucfirst(sprintf(
                dp_text('%s gives %s the old "Jedi mind-trick" stink eye.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                $what_ob->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
                $this, $what_ob);
            }
        $what_ob->tell(ucfirst(sprintf(
            dp_text('%s gives you the old "Jedi mind-trick" stink eye.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));

        $what_ob->moveDpObject($where_ob);
        return TRUE;
    }

    /**
     * Resets the environment.
     *
     * @return  boolean TRUE
     */
    function actionReset()
    {
        $this->getEnvironment()->__reset();
        $this->tell('Resetted.<br />');
        return TRUE;
    }

    /**
     * Go to the login page, logout if logon on.
     *
     * @return  boolean TRUE
     */
    function actionLoginOut()
    {
        $this->tell('<location>/page/login.php'
            . ($this->isRegistered ? '&act=logout' : '')
            . '</location>');
        return TRUE;
    }

    /**
     * Moves to or sets personal home location
     *
     * @param   string  $verb       the action, "myhome"
     * @param   string  $noun       empty to go home, "set" to set home
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionMyhome($verb, $noun)
    {
        if (is_null($noun)) {
            $result = dp_db_query('
                SELECT
                    userHomeLocation,userHomeSublocation
                FROM
                    Users
                WHERE
                    userUsernameLower='
                    . dp_db_quote(dp_strtolower($this->getTitle()), 'text'));

            if (empty($result) || !($row = dp_db_fetch_row($result))) {
                dp_db_free($result);
                return FALSE;
            }

            if (is_null($row[0])) {
                $this->tell(dp_text('You have no home location set. Set your home location first.<br />'));
                dp_db_free($result);
                return TRUE;
            }

            dp_db_free($result);
            $this->tell('<location>' . $row[0] . (is_null($row[1]) ? ''
                : '&sublocation=' . $row[1]) . '</location>');
            return TRUE;
        }

        if (dp_text("set") === $noun) {
            $env = $this->getEnvironment();
            if (!$env || !dp_strlen($loc = $env->location)) {
                return FALSE;
            }
            $subloc = $env->sublocation;

            dp_db_exec('UPDATE Users set userHomeLocation='
                . dp_db_quote($loc, 'text') . ',userHomeSublocation='
                . (is_null($subloc) || !dp_strlen($subloc) ? 'NULL'
                : dp_db_quote($subloc, 'text')) . ' WHERE userUsernameLower='
                . dp_db_quote(dp_strtolower($this->getTitle()), 'text'));
            $this->tell(dp_text('Your home location has been set to the current page.<br />'));
            return TRUE;
        }

        $this->actionFailure = dp_text('Syntax: myhome [set]<br />');
        return FALSE;
    }

    /**
     * Sets the input field mode
     *
     * Without an argument, switches between the two modes "cmd" and "say".
     * Otherwise switches to the given mode (if it is valid). The input field
     * stays visible between page changes with mode "pin".
     *
     * @param   string  $verb       the action, "mode"
     * @param   string  $noun       empty or a mode
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionMode($verb, $noun)
    {
        if (is_null($noun)) {
            $this->inputMode = 'cmd' !== $this->inputMode ? 'cmd' : 'say';
        } elseif (in_array($noun, explode('#', dp_text('cmd#command')))) {
            $this->inputMode = 'cmd';
        } elseif (in_array($noun, explode('#', dp_text('say')))) {
            $this->inputMode = 'say';
        } elseif (in_array($noun, explode('#', dp_text('once')))) {
            $ip = 'once';
        } elseif (in_array($noun, explode('#', dp_text('page')))) {
            $ip = 'page';
        } elseif (in_array($noun, explode('#', dp_text('always')))) {
            $ip = 'always';
        } else {
            $this->actionFailure = dp_text('Invalid action mode (valid modes are: say and cmd).<br />');
            return FALSE;
        }

        if (isset($ip)) {
            $this->inputPersistent = $ip;
            if ($this->isRegistered) {
                dp_db_exec('UPDATE Users SET '
                    . 'userInputPersistent=' . dp_db_quote($ip, 'text')
                    . ' WHERE userUsernameLower='
                    . dp_db_quote(dp_strtolower($this->getTitle()), 'text'));
            }
            return TRUE;
        }

        if ($this->isRegistered) {
            dp_db_exec('UPDATE Users set userInputMode='
                . dp_db_quote($this->inputMode, 'text')
                . ' WHERE userUsernameLower='
                . dp_db_quote(dp_strtolower($this->getTitle()), 'text'));
        }
        $this->tell('cmd' === $this->inputMode
            ? dp_text('The input field is now in command mode. Enter <tt>help</tt> for more information.<br />')
            : dp_text('The input field is now in page chat mode. Enter <tt>/help</tt> for more information.<br />'));
        return TRUE;
    }

    function getModeChecked()
    {
        return ('cmd' !== $this->inputMode ? '' : '<img src="'
            . DPUNIVERSE_IMAGE_URL . 'checked.gif" width="7" '
            . 'height="7" border="0" alt="" title="" />#'
            . DPUNIVERSE_IMAGE_URL . 'checked_over.gif#')
            . dp_text('command mode');
    }

    /**
     * Makes this user object tell something to another user object
     *
     * @param   string  $verb       the action, "tell"
     * @param   string  $noun       who and what to tell, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionTell($verb, $noun)
    {
        if (FALSE === ($pos = dp_strpos($noun, ' '))) {
            $this->tell('<script>
setTimeout("bind_input(); _gel(\'dptell\').focus();", 50);
</script>');
            $this->tell('<window styleclass="dpwindow_tell">
<b>' . sprintf(dp_text('Private message to %s:'), $noun) . '</b><br /><br />
<form onSubmit="send_action2server(\''
                . addslashes($verb) . ' ' . addslashes($noun)
                . ' \' + jQuery(\'#dptell\').val()); close_dpwindow(); '
                . 'return false">
<input id="dptell" type="text" value="" size="50" maxlength="255"
class="dpcomm" /> <input type="submit" value=" &gt; " /></form>
</window>');
            return TRUE;
        }

        return DpLiving::actionTell($verb, $noun);
    }

    /**
     * Makes this user object shout something to everyone on the site
     *
     * @param   string  $verb       the action, "shout"
     * @param   string  $noun       what to shout, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionShout($verb, $noun)
    {
        if (is_null($noun)) {
            $this->tell('<script>
setTimeout("bind_input(); _gel(\'dpshout\').focus();", 50);
</script>');
            $this->tell('<window styleclass="dpwindow_shout">
<b>' . dp_text('Message to everybody on this site:') . '</b><br /><br />
<form onSubmit="send_action2server(\''
                . addslashes($verb)
                . ' \' + jQuery(\'#dpshout\').val()); close_dpwindow(); '
                . 'return false">
<input id="dpshout" type="text" value="" size="50" maxlength="255"
class="dpcomm" /> <input type="submit" value=" &gt; " /></form>
</window>');
            return TRUE;
        }

        return DpLiving::actionShout($verb, $noun);
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
        if (is_null($noun)) {
            $this->tell('<script>
setTimeout("bind_input(); _gel(\'dpemote\').focus();", 50);
</script>');
            $this->tell('<window styleclass="dpwindow_emote">
<b>' . dp_text('Emotion to everybody on this page:') . '</b><br /><br />'
                . $this->title . ' <form onSubmit="send_action2server(\''
                . addslashes($verb)
                . ' \' + jQuery(\'#dpemote\').val()); close_dpwindow(); '
                . 'return false">
<input id="dpemote" type="text" value="" size="50" maxlength="255"
class="dpemote" /> <input type="submit" value=" &gt; " /></form>
</window>');
            return TRUE;
        }

        return DpLiving::actionEmote($verb, $noun);
    }


}
?>
