<?php
/**
 * A user object, the object representing a real user
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
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpUser.php 185 2007-06-09 21:53:43Z ls $
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
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.0
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

    private $mLastStatus = FALSE;

    public $mAlertEvents = array();

    public $mActionHistory = array();

    /**
     * Initializes the user
     */
    function createDpLiving()
    {
        $this->addId(dptext('user'));
        $this->isUser = new_dp_property(TRUE);
        $this->isRegistered = new_dp_property(FALSE);
        $this->isAdmin = new_dp_property(FALSE);
        $this->noCookies = new_dp_property(FALSE);
        $this->isKnownBot = new_dp_property(FALSE);
        $this->isAjaxCapable = new_dp_property(FALSE);
        $this->age = new_dp_property(0, FALSE);
        $this->inactive = new_dp_property(FALSE, FALSE, 'getInactive');
        $this->isInactive = new_dp_property(FALSE, FALSE, 'isInactive');

        $avatar_nr = $this->_getRandAvatarNr();
        $this->avatarNr = new_dp_property($avatar_nr);
        $this->setTitle(dptext('User'));
        $this->titleType = DPUNIVERSE_TITLE_TYPE_NAME;
        $this->titleImg = DPUNIVERSE_AVATAR_URL . 'user' . $avatar_nr . '.gif';
        $this->status = new_dp_property(FALSE, NULL, 'getStatus');
        $this->setBody('<img src="' . DPUNIVERSE_AVATAR_URL . 'user'
            . $avatar_nr . '_body.gif" border="0" alt="" align="left" '
            . 'style="margin-right: 15px" />' . dptext('A user.') . '<br />');

        /* Actions for everybody */
        $this->addAction(dptext("who's here?"), dptext('who'), 'actionWho', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('help'), dptext('help'), 'actionHelp', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('settings'), explode('#', dptext('settings#config')), 'actionSettings', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('source'), dptext('source'), 'actionSource', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('page links'), dptext('links'), 'actionLinks', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);

        /* Actions for admin only */
        $this->addAction(dptext('test'), dptext('test'), 'actionTest', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('reset'), dptext('reset'), 'actionReset', DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('svars'), dptext('svars'), 'actionSvars', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('force'), dptext('force'), 'actionForce', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);
        $this->addAction(dptext('move'), dptext('move'), 'actionMove', DP_ACTION_OPERANT_COMPLETE, DP_ACTION_TARGET_LIVING, DP_ACTION_AUTHORIZED_ADMIN, DP_ACTION_SCOPE_SELF);

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

    protected function getAge()
    {
        if (!$this->isRegistered) {
            return FALSE;
        }

        $result = mysql_query("SELECT userAge FROM Users WHERE "
            . "userUsernameLower='" . strtolower($this->getTitle()) . "'");

        $age = !$result || !mysql_num_rows($result)
            ? 0 : mysql_result($result, 0, 0);

        return get_age2string($age + (time() - $this->creationTime));

    }

    function getInactive()
    {
        $inactive_time = time() - (!isset($this->lastActionTime)
            ? $this->creationTime : $this->lastActionTime);

        if ($inactive_time < 300) {
            return '';
        }

        return get_age2string($inactive_time);
    }

    function isInactive()
    {
        $inactive_time = time() - (!isset($this->lastActionTime)
            ? $this->creationTime : $this->lastActionTime);

        return $inactive_time >= 300;
    }

    function getStatus()
    {
        if ($this->isInactive) {
            return dptext('away');
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
        if ((FALSE === is_string($data) || 0 === strlen($data)) &&
                (FALSE === is_array($data)) || 0 === count($data)) {
            return;
        }

        if (FALSE === is_null($binded_env)
                && (FALSE === ($env = $this->getEnvironment())
                || $env !== $binded_env)) {
            echo dptext("Message skipped, no longer in page\n");
            return;
        }

        // If this user is the same user doing the current HTTP request, tell
        // straight away. Otherwise, store the message for the next time we get
        // a HTTP request from the user.
        if (TRUE === get_current_dpuniverse()->isNoDirectTell()
                || $this !== get_current_dpuser()) {
            //echo sprintf(dptext("Storing %s: %s\n"), $this->getTitle(),
            //    (strlen($data) > 512 ? substr($data, 0, 512) : $data));
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
         if (strlen($data) >=3 && substr($data, 0, 1) == '<'
                && FALSE !== ($pos = strpos($data, '>'))) {
            $mtype_start = substr($data, 1, $pos - 1);
            $endpos = strrpos($data, '<');
            $mtype_end = substr($data, $endpos + 2, -1);

            $data = "<![CDATA[" . substr($data, strlen($mtype_start) + 2,
                $endpos - strlen($mtype_start) - 2) . ']]>';
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
            if ($data !== '1') {
                echo sprintf(dptext("Telling %s: %s\n"), $this->getTitle(),
                    (strlen($data) > 256 ? substr($data, 0, 256) : $data));
            }
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

    function eventDpLiving($name)
    {
        if (EVENT_DESTROYING_OBJ !== $name || !isset($this->isRegistered)
                || TRUE !== $this->isRegistered) {
            return;
        }

        $result = mysql_query("SELECT userAge FROM Users WHERE "
            . "userUsernameLower='" . strtolower($this->getTitle()) . "'");

        $age = !$result || !mysql_num_rows($result)
            ? 0 : mysql_result($result, 0, 0);

        $new_age = $age + (time() - $this->creationTime);
        mysql_query($query = "UPDATE Users set userAge='{$new_age}' WHERE "
            . "userUsernameLower='" . strtolower($this->getTitle()) . "'");
    }

    function isDraggable($by_who)
    {
        if ($by_who === $this) {
            return TRUE;
        }

        return DpLiving::isDraggable($by_who);
    }

    final public function performAction($action)
    {
        $action = trim($action);
        if ('' !== $action && isset($this->_GET['cmdline'])
             && (!($sz = count($this->mActionHistory))
             || $this->mActionHistory[$sz - 1] !== $action)) {
            $this->mActionHistory[] = $action;
            if (count($this->mActionHistory) > 20) {
                array_shift($this->mActionHistory);
            }
        }
        return DpLiving::performAction($action);
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
            foreach ($links as $linktitle => $linkdata) {
                if ($linktitle === DPUNIVERSE_NAVLOGO) {
                    $linkcommand = dptext('home');
                } else {
                    $linkcommand = explode(' ', $linktitle);
                    $linkcommand = strtolower($linktitle);
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
           $this->tell('<window><b>' . dptext('No one is on this site.')
            . '</b></window>');
            return TRUE;
        }

        $tell = '<b>' . dptext('People currently on this site:') . '</b><br />';
        $tell .= '<table cellpadding="0" cellspacing="0" border="0" style="'
            . 'margin-top: 5px">';
        foreach ($users as &$user) {
            $env = $user->getEnvironment();
            $loc = $env->location;
            if (0 !== strpos($loc, 'http://')) {
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

    $.ajax({
        url: "' . DPSERVER_CLIENT_URL . '",
        data: "location='
            . $this->getEnvironment()->location
            . '&rand="+Math.round(Math.random()*9999)
            + "&call_object="+escape("' . $this->getUniqueId() . '")
            + "&method=setSettings"
            + "&avatar_nr="+avatar_nr
            + "&display_mode="+(_gel("display_mode1").checked ? _gel("display_mode1").value : _gel("display_mode2").value)
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

        $this->tell('<window>' . dptext('Choose your avatar:') . '<br />'
. $avatar_settings . '<br /><br />'
. dptext('People and items on the page are displayed in:') . '<br />
<input type="radio" id="display_mode1" name="display_mode" value="graphical"' . ($this->displayMode == 'graphical' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dptext('Graphical mode') . '<br />
<input type="radio" id="display_mode2" name="display_mode" value="abstract"' . ($this->displayMode == 'abstract' ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dptext('Abstract mode') . '<br /><br />'
. dptext('Alert me of the following events:') . '<br />
<input type="checkbox" id="people_entering" name="people_entering" value="1"' . (isset($this->mAlertEvents['people_entering']) ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dptext('People entering this site') . '<br />
<input type="checkbox" id="people_leaving" name="people_leaving" value="1"' . (isset($this->mAlertEvents['people_leaving']) ? ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dptext('People leaving this site') . '<br />
<input type="checkbox" id="bots_entering" name="bots_entering" value="1"' . (isset($this->mAlertEvents['bots_entering']) ?  ' checked="checked"' : '') . ' onClick="send_settings()" style="cursor: pointer" />' . dptext('Search engines indexing pages') . '<br /><br />
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
            $this->tell(dptext('Error receiving settings.<br />'));
        }

        $this->avatarNr = $avatar_nr;
        $this->setTitleImg(DPUNIVERSE_AVATAR_URL . 'user' . $avatar_nr
            . '.gif');
        $this->setBody('<img src="' . DPUNIVERSE_AVATAR_URL . 'user'
            . $avatar_nr . '_body.gif" border="0" alt="" align="left" '
            . 'style="margin-right: 15px" />' . dptext('A user.') . '<br />');

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
            mysql_query($query = "UPDATE Users set "
                . "userAvatarNr='" . addslashes($avatar_nr)
                . "',userEventPeopleEntering='"
                . (!isset($this->mAlertEvents['people_entering']) ? '0' : '1')
                . "',userEventPeopleLeaving='"
                . (!isset($this->mAlertEvents['people_leaving']) ? '0' : '1')
                . "',userEventBotsEntering='"
                . (!isset($this->mAlertEvents['bots_entering']) ? '0' : '1')
                . "',userDisplayMode='" . addslashes($display_mode)
                . "' WHERE userUsernameLower='"
                . strtolower($this->getTitle()) . "'");
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
            . '</b><pre>' . htmlentities(print_r($ob->getProperties(), TRUE))
            . '</pre></window>');
        return TRUE;
    }

    function actionTest($verb, $noun)
    {
        if ('1a' == $noun) {
            $this->setWeight(0);
            $this->tell('weight property test member access:');
            $this->tell('wt: ' . $this->weight);
            $this->weight = -1;
            $this->tell('wt: ' . $this->weight);
            $this->weight = 0;
            $this->tell('wt: ' . $this->weight);
            $this->weight = 1;
            $this->tell('wt: ' . $this->weight);
            $this->weight = 10;
            $this->tell('wt: ' . $this->weight);
            $this->weight = 20;
            $this->tell('wt: ' . $this->weight);
        } elseif ('1b' == $noun) {
            $this->setWeight(0);
            $this->tell('weight property test method access:');
            $this->tell('wt: ' . $this->getWeight());
            $this->setWeight(-1);
            $this->tell('wt: ' . $this->getWeight());
            $this->setWeight(0);
            $this->tell('wt: ' . $this->getWeight());
            $this->setWeight(1);
            $this->tell('wt: ' . $this->getWeight());
            $this->setWeight(10);
            $this->tell('wt: ' . $this->getWeight());
            $this->setWeight(20);
            $this->tell('wt: ' . $this->getWeight());
        } elseif ('2a' == $noun) {
            $this->setValue(0);
            $this->tell('value property test member access:');
            $this->tell('value: ' . $this->value);
            $this->value = -1;
            $this->tell('value: ' . $this->value);
            $this->value = 0;
            $this->tell('value: ' . $this->value);
            $this->value = 1;
            $this->tell('value: ' . $this->value);
            $this->value = 10;
            $this->tell('value: ' . $this->value);
            $this->value = 1001;
            $this->tell('value: ' . $this->value);
        } elseif ('2b' == $noun) {
            $this->setValue(0);
            $this->tell('value property test method access:');
            $this->tell('value: ' . $this->getValue());
            $this->setValue(-1);
            $this->tell('value: ' . $this->getValue());
            $this->setValue(0);
            $this->tell('value: ' . $this->getValue());
            $this->setValue(1);
            $this->tell('value: ' . $this->getValue());
            $this->setValue(10, 'USD');
            $this->tell('value: ' . $this->getValue());
            $this->setValue(1001);
            $this->tell('value: ' . $this->getValue());
        } elseif ('3' == $noun) {
            $inv = $this->getInventory();
            foreach ($inv as &$ob) {
                $this->tell($ob->getTitle() . ': ' . $ob->getWeight());
            }
        }
        else {
            $this->tell('test &lt;1a|1b|2a|2b|3&gt;<br />');
        }

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

    function actionReset($verb, $noun)
    {
        $this->getEnvironment()->__reset();
        $this->tell('Resetted.<br />');
        return TRUE;
    }
}
?>
