<?php
/**
 * Handles the current HTTP page or AJAX request by the user
 *
 * DutchPIPE version 0.2; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage lib
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpcurrentrequest.php 243 2007-07-08 16:26:23Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php
 */

/**
 * Handles the current HTTP page or AJAX request by the user
 *
 * Each time a user requests a page or performs an AJAX check, an instance of
 * this class is created by the universe object, user data is set, and methods
 * are called in it to handle this user. After that the object is destroyed.
 *
 * @access     private
 * @package    DutchPIPE
 * @subpackage lib
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpCurrentRequest
{
    /**
     * @var         object     Reference to the universe object
     * @access      private
     */
    private $mrDpUniverse;

    /**
     * @var         array      User server variables
     * @access      private
     */
    private $__SERVER;

    /**
     * @var         array      User session variables
     * @access      private
     */
    private $__SESSION;

    /**
     * @var         array      User cookie variables
     * @access      private
     */
    private $__COOKIE;

    /**
     * @var         array      User get variables
     * @access      private
     */
    private $__GET;

    /**
     * @var         array      User post variables
     * @access      private
     */
    private $__POST;

    /**
     * @var         array      User files variables
     * @access      private
     */
    private $__FILES;

    /**
     * @var         object     Reference to the environment of user
     * @access      private
     */
    private $mrEnvironment;

    /**
     * @var         object     Reference to the user object in the universe
     * @access      private
     */
    private $mrUser;

    /**
     * @var         boolean    Is this request coming from a registered user?
     * @access      private
     */
    private $mIsRegistered = FALSE;

    /**
     * @var         string     The user name behind the current client request
     * @access      private
     */
    private $mUsername;

    /**
     * @var         string     The user's avatar number
     * @access      private
     */
    private $mUserAvatarNr = FALSE;

    /**
     * @var         string     The user's display mode (abstract or graphical)
     * @access      private
     */
    private $mUserDisplayMode = FALSE;

    /**
     * @var         string     Events shown to the current user
     * @access      private
     */
    private $mUserAlertEvents = FALSE;

    /**
     * The 'cookie id' of the user client behind the current request
     *
     * We don't store the users real username and password in his cookie, but
     * something we generated and stored in the user's database entry. If the
     * cookie is stolen, the username/password isn't.
     *
     * @var         string     The cookie id of the current user
     * @access      private
     */
    private $mCookieId;

    /**
     * The 'cookie password' of the user client behind the current request
     *
     * We don't store the users real username and password in his cookie, but
     * something we generated and stored in the user's database entry. If the
     * cookie is stolen, the username/password isn't.
     *
     * @var         string     The cookie password of the current user
     * @access      private
     */
    private $mCookiePass;

    /**
     * @var         boolean    Has the user been told anything this request?
     * @access      private
     */
    private $mToldSomething;

    /**
     * Used if the experimental PHP runkit module is used. If for instance a
     * page object contins a syntax error and a user tries to go there, this
     * flag will be true.
     *
     * @ignore
     * @var         boolean    TRUE if the user tried to move but failed
     * @access      private
     */
    /* private $mMoveError; */

    /**
     * :KLUDGE: Used as a kludge to handle user movements within this request
     *
     * @var         boolean    TRUE if the user moved
     * @access      private
     */
    private $mHasMoved;

    /**
     * @var         boolean    Is this user a known search bot/spider/crawler?
     * @access      private
     */
    private $mIsKnownBot = FALSE;

    /**
     * @var         mixed      User agent string reported by browser or FALSE
     * @access      private
     */
    private $mUserAgent;

    /**
     * Sets up the object handling the current user request
     *
     * @param   array   $rDpUniverse  Reference to the universe object
     * @param   array   $rServerVars  User server variables
     * @param   array   $rSessionVars User session variables
     * @param   array   $rCookieVars  User cookie variables
     * @param   array   $rGetVars     User get variables
     * @param   array   $rPostVars    User post variables
     * @param   array   $rFilesVars   User files variables
     */
    function __construct(&$rDpUniverse, &$rServerVars, &$rSessionVars,
                         &$rCookieVars, &$rGetVars, &$rPostVars, &$rFilesVars)
    {
        $this->mrDpUniverse = $rDpUniverse;

        if (!is_null($rServerVars)) {
            $this->__SERVER = $rServerVars;
        }
        if (!is_null($rSessionVars)) {
            $this->__SESSION = $rSessionVars;
        }
        if (!is_null($rCookieVars)) {
            $this->__COOKIE = $rCookieVars;
        }
        if (!is_null($rGetVars)) {
            $this->__GET = $rGetVars;
        }
        if (!is_null($rPostVars)) {
            $this->__POST = $rPostVars;
        }
        if (!is_null($rFilesVars)) {
            $this->__FILES = $rFilesVars;
        }
    }

    /**
     * Handles the HTTP request to the server
     */
    function handleRequest()
    {
        if (FALSE === $this->_findOrCreateDpUser()) {
            return;
        }
        if (isset($this->__GET['gethistory'])) {
            $this->mrUser->tell('<history>' . implode('@SEP@',
                $this->mrUser->mActionHistory) . '</history>');
            return;
        }

        $this->mrUser->setVars($this->__SERVER, $this->__SESSION,
            $this->__COOKIE, $this->__GET, $this->__POST, $this->__FILES);

        $this->_handleLocation();
    }

    /**
     * Finds if user is already on this site, otherwise creates a user object
     *
     * Tries to find cookie info that goes with the HTTP request to link the
     * request to a user object already on this site. Otherwise, creates either
     * a new guest or registered user object in the universe. Registered users
     * should have a cookie set in their browser with login info.
     *
     * Returns true if a user object was found or created, or FALSE in case of
     * an error.
     *
     * @return  boolean TRUE for user object found or created, FALSE otherwise
     */
    private function _findOrCreateDpUser()
    {
        if (TRUE === $this->_findCookieData() &&
                FALSE !== ($u = $this->mrDpUniverse->findCurrentDpUser(
                $this->mCookieId, $this->mCookiePass))) {
            /* This user is already on this site */
            $this->_initExistingDpUser($u);
            return TRUE;
        }

        if (isset($this->__GET) && isset($this->__GET['ajax']) &&
                (!isset($this->__GET['standalone']) ||
                (isset($this->__GET['seq']) && (int)$this->__GET['seq'] > 0))) {
            /*
             * There's an AJAX request coming from someone without cookies.
             * The DutchPIPE cookie should have been set in the page request
             * which comes first.
             */
            $this->mCookieId = FALSE;
            //echo dptext("NO COOKIE\n");
            return FALSE;
        }
        /*
         * A page request from someone entering the site. Initialize data
         * for either a registered user (by obtaining a cookie) or a guest
         * user.
         */
        if (FALSE === $this->_findAndInitRegisteredDpUser() &&
                FALSE === $this->_initDpGuest()) {
            return FALSE;
        }

        /* Create the new user object with the initialized data */
        $this->_createDpUser();
        return TRUE;
    }

    /**
     * Checks if the current connection can be tied to a user.
     *
     * Checks and possibly creates user and location objects, handles passing of
     * variables such server, cookie and get arrays.
     *
     * @return  boolean TRUE for valid user request, FALSE otherwise
     */
    private function _findCookieData()
    {
        if (isset($this->__COOKIE)
                && isset($this->__COOKIE[DPSERVER_COOKIE_NAME])
                && strlen($this->__COOKIE[DPSERVER_COOKIE_NAME])
                && strlen($cookie_data =
                    trim($this->__COOKIE[DPSERVER_COOKIE_NAME], ';'))
                && sizeof($cookie_data = explode(';', $cookie_data))
                && 2 === sizeof($cookie_data)) {
            $this->mCookieId = $cookie_data[0];
            $this->mCookiePass = $cookie_data[1];

            return strlen($this->mCookieId) && strlen($this->mCookiePass);
        }
        return FALSE;
    }

    /**
     * Initializes this request's user data for an existing user in the universe
     *
     * @param   array   $user       information about this user in the universe
     */
    private function _initExistingDpUser(&$user)
    {
        $this->mrUser = &$user[_DPUSER_OBJECT];
        $this->mUsername = $user[_DPUSER_NAME];

        if ($user[_DPUSER_ISREGISTERED] == '1') {
            $this->mrUser->isRegistered = TRUE;
            $this->mIsRegistered = TRUE;
        } else {
            $this->mrUser->isRegistered = FALSE;
        }
    }

    /**
     * Finds and initializes user data for a registered user entering the site
     *
     * If cookie data was sent by the browser and it is valid data for a
     * registered user, TRUE is returned, otherwise FALSE is returned.
     *
     * @return  boolean TRUE for request from registered user, FALSE otherwise
     */
    private function _findAndInitRegisteredDpUser()
    {
        /*
         * A registered user. Skip Ajax database check for now, but what
         * are the security implications?
         */

        if (!isset($this->mCookieId) || !isset($this->mCookiePass)) {
            return FALSE;
        }

        $result = mysql_query("
            SELECT
                userUsername,userAvatarNr,userDisplayMode,
                userEventPeopleEntering,userEventPeopleLeaving,
                userEventBotsEntering
            FROM
                Users
            WHERE
                userCookieId='" . addslashes($this->mCookieId) . "'
            AND
                userCookiePassword='" . addslashes($this->mCookiePass) . "'
            ");

        if (empty($result) || !($row = mysql_fetch_array($result))) {
            return FALSE;
        }

        $this->mUsername = $row[0];
        $this->mUserAvatarNr = $row[1];
        $this->mUserDisplayMode = $row[2];
        $alert_events = array();
        if ('1' === $row[3]) {
            $alert_events['people_entering'] = TRUE;
        }
        if ('1' === $row[4]) {
            $alert_events['people_leaving'] = TRUE;
        }
        if ('1' === $row[5]) {
            $alert_events['bots_entering'] = TRUE;
        }
        if (count($alert_events)) {
            $this->mUserAlertEvents = $alert_events;
        }

        $this->mIsRegistered = TRUE;

        return TRUE;
    }
    /**
     * Initializes guest variables, sets cookie
     *
     * Sets up a random Guest#X user name and sends a cookie to the guest to
     * store user information.
     *
     * Some experimental stuff is going on with search bots, so you can see
     * them crawling the site. This will later be turned into an option.
     *
     * FALSE is returned if the user's client didn't report a "user agent"
     * string. This is mandatory. The guest member variables will not be set up.
     *
     * @return  boolean TRUE for succesful setup, FALSE otherwise
     */
    private function _initDpGuest()
    {
        $this->mIsKnownBot = $this->_findAgentAndKnownBots();

        if (FALSE === $this->mUserAgent) {
            return FALSE;
        }

        if (FALSE === $this->mIsKnownBot) {
            $this->mUsername = sprintf(dptext('Guest#%d'),
                $this->mrDpUniverse->getGuestCnt());
        } else {
            $this->mUsername = $this->mIsKnownBot;
        }

        $this->mCookieId = make_random_id();
        $this->mCookiePass = make_random_id();
        $this->mIsRegistered = FALSE;

        //echo "Set-Login: {$this->mCookieId};{$this->mCookiePass}\n";
        $this->mrDpUniverse->tellCurrentDpUserRequest(
            "Set-Login: {$this->mCookieId};{$this->mCookiePass}");
        return TRUE;
    }

    /**
     * Finds if the HTTP request came from a known search bot
     *
     * Every browser sends a "user agent" string, DutchPIPE stores some of the
     * more well-known user agent strings for search bots in the database, and
     * assigns them a special avatar when they enter the site.
     *
     * :WARNING: Browsers/spiders/bots that do not report a user agent, are
     * denied access.
     *
     * @return  string|boolean Avatar title for known bots, FALSE otherwise
     */
    private function _findAgentAndKnownBots()
    {
        /* Gets the browser name of the user, a.k.a. the user agent */
        $this->mUserAgent = !isset($this->__SERVER['HTTP_USER_AGENT'])
                || 0 === strlen($this->__SERVER['HTTP_USER_AGENT'])
            ? FALSE : $this->__SERVER['HTTP_USER_AGENT'];

        if (FALSE === $this->mUserAgent) {
            //echo dptext("No agent\n");
            return FALSE;
        }

        /*
         * Give special guest names to some well known search bots.
         * Otherwise the name will be Guest#<number>.
         */
        $result = mysql_query("SELECT userAgentTitle FROM UserAgentTitles "
            . "WHERE userAgentString='{$this->mUserAgent}'");

        return $this->mIsKnownBot = (FALSE === $result
            || !($row = mysql_fetch_array($result)) ? FALSE : $row[0]);
    }

    /**
     * Gets the current user object
     *
     * @return  object  the current user object or FALSE for no user
     */
    function &getUser()
    {
        $rval = !isset($this->mrUser) ? FALSE : $this->mrUser;
        return $rval;
    }

    /**
     * Sets the current user's name
     *
     * @param   string  $userName   name of the current user
     */
    function setUsername($userName)
    {
        $this->mUsername = $userName;
    }

    /**
     * Checks if a cookie was succesfully set in the user's browser
     *
     * @return  boolean TRUE if cookie enabled, FALSE otherwise
     */
    function isCookieEnabled()
    {
        return FALSE !== $this->mCookieId;
    }

    /**
     * Creates a new user object in the universe
     *
     * An object to represent either a guest or a registered user in the
     * universe is created, and added to the universe's user list. This method
     * should only be called if the user does not exist yet in the universe,
     * that is, the user entered the site. The user is created based on member
     * values set in this object. After this method was called, the user object
     * has been fully set-up, but has no environment yet.
     */
    function _createDpUser()
    {
        //echo "_createDpUser()\n";
        $this->mrUser = $this->mrDpUniverse->newDpObject(DPUNIVERSE_STD_PATH
            . 'DpUser.php');
        $this->mrUser->addId($this->mUsername);
        $this->mrUser->setTitle(ucfirst($this->mUsername));

        if (FALSE !== $this->mUserAvatarNr) {
            $this->mrUser->avatarNr = $this->mUserAvatarNr;
            $this->mrUser->setTitleImg(DPUNIVERSE_AVATAR_URL . 'user'
                . $this->mUserAvatarNr . '.gif');
            $this->mrUser->setBody('<img src="' . DPUNIVERSE_AVATAR_URL
                . 'user' . $this->mUserAvatarNr . '_body.gif" border="0" '
                . 'alt="" align="left" style="margin-right: 15px" />'
                . dptext('A user.') . '<br />');
        }

        if (FALSE !== $this->mUserDisplayMode) {
            $this->mrUser->displayMode = $this->mUserDisplayMode;
        }

        if (FALSE !== $this->mUserAlertEvents) {
            $this->mrUser->mAlertEvents = $this->mUserAlertEvents;
            foreach ($this->mUserAlertEvents as $event => $foo) {
                get_current_dpuniverse()->addAlertEvent($event, $this->mrUser);
            }
        }

        if (FALSE !== $this->mIsKnownBot) {
            $this->mrUser->setTitleImg(DPUNIVERSE_IMAGE_URL . 'bot.gif');
            $this->mrUser->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
                . 'bot.gif" border="0" alt="" align="left" '
                . 'style="margin-right: 15px" />'
                . dptext('This is a search engine indexing this site.<br />'));
            $this->mrUser->isKnownBot = TRUE;
        }

        if (FALSE !== $this->mIsRegistered) {
            $this->mrUser->isRegistered = TRUE;
        }
        if (in_array($this->mUsername,
                explode('#', DPUNIVERSE_ADMINISTRATORS))) {
            $this->mrUser->isAdmin = TRUE;
        }
        if (FALSE === $this->mCookieId) {
            $this->mrUser->noCookies = TRUE;
        }

        $this->mrDpUniverse->addDpUser($this->mrUser, $this->mUsername,
            $this->mCookieId, $this->mCookiePass, $this->mIsRegistered);

        echo sprintf(dptext("User %s created\n"), $this->mUsername);
    }

    /**
     * Checks the location of the user currently connected.
     *
     * Checks if the user moved or entered the site, so the user gets a new
     * or a first environment. Also handles pages "outside" dpuniverse and
     * method calls from the AJAX client.
     */
    function _handleLocation()
    {
        /* Initializes a location based on the location=x part in the URL */
        $this->_getLocation();
        //echo "_handleLocation()... " . $this->__GET['location']  . "\n";

        //echo sprintf(dptext("Getting location %s given by client for %s\n"),
        //    $this->__GET['location'], $this->mUsername);

        $sublocation = !isset($this->__GET['sublocation']) ? FALSE
            : $this->__GET['sublocation'];

        $tmp = $this->mrDpUniverse->getDpObject($this->__GET['location'],
            $sublocation);
        if (FALSE === $tmp) {
            $tmp = $this->mrDpUniverse->getDpObject(DPUNIVERSE_PAGE_PATH
                . 'index.php');
            $this->mrUser->tell('<location>' . DPSERVER_CLIENT_DIR
                . '</location>');
        } elseif (!$tmp->standaloneTitleSet && isset($this->__GET['title'])) {
            /*
             * Standalone pages send their title in the first AJAX call. Could
             * be improved as it always does that, while it is only needed once.
             */
            $tmp->title = $this->__GET['title'];
            $tmp->standaloneTitleSet = new_dp_property(TRUE);
        }

        //echo "\n";
        $this->mrEnvironment = $tmp;

        if (FALSE === ($from_env = $this->mrUser->getEnvironment())
                || $from_env !== $this->mrEnvironment) {
            /* User entered the site or left to another environment */
            $this->_handleNewEnvironment($from_env);
        }
        elseif (!isset($this->__GET['ajax'])
                && !isset($this->__GET['method'])) {
            /* A page request from a user whose environment did not change */
            $this->_handleReloadEnvironment();
        }
        elseif (isset($this->__GET['method'])) {
            $this->_handleMethodCall();
        }

        if (isset($this->__GET['getdivs'])) {
            $this->_handleGetDivsCall();
        }
        $this->mToldSomething = FALSE;
    }

    /**
     * Initialize a location based on the location=x part in the URL.
     */
    private function _getLocation()
    {
        //echo "_getLocation()... ";
        if (FALSE === $this->_isValidLocation()) {
            //echo "Not valid location\n";
            $tmp = $this->mrUser->getEnvironment();
            if (isset($this->__GET['method']) && FALSE !== $tmp) {
                $this->__GET['location'] = $tmp->location;
                $this->__GET['sublocation'] = $tmp->sublocation;
            } elseif (isset($this->mrUser->_GET['proxy'])
                    || (FALSE !== $tmp && (isset($this->mrUser->_GET['ajax'])
                    || isset($this->__GET['method']))
                    && isset($tmp->isLayered) && FALSE !== $tmp->isLayered)) {
                $this->__GET['location'] = $tmp->location;
            } else {
                $this->__GET['location'] = DPUNIVERSE_PAGE_PATH . 'index.php';
            }
        } else {
            //echo "Valid location\n";
        }
    }

    /**
     * Checks if the given location exists
     *
     * @return  boolean TRUE for valid location, FALSE otherwise
     */
    private function _isValidLocation()
    {
        //echo dptext("_isValidLocation: checking location \"%s\"\n",
        //    !isset($this->__GET['location']) ? '' : $this->__GET['location']);
        if (isset($this->__GET['location'])
                && !strlen($this->__GET['location'])) {
            return TRUE;
        }

        if (!isset($this->__GET['location'])) {
            return isset($this->__GET['getdivs']);
        }

        if (FALSE !== strpos($this->__GET['location'], '..')) {
            return FALSE;
        }

        if (FALSE !== ($pos = strpos($this->__GET['location'], '?'))) {
            $this->__GET['location'] =
                substr($this->__GET['location'], 0, $pos);
        }

        if (FALSE !== ($pos = strpos($this->__GET['location'], '#'))) {
            $this->__GET['location'] =
                substr($this->__GET['location'], 0, $pos);
        }

        /* Experimental */
        if (isset($this->__GET['proxy'])
                || 0 === strpos($this->__GET['location'], '/mailman2/')) {
            return TRUE;
        }

        if (($len = strlen(DPSERVER_HOST_URL)) < strlen($this->__GET['location'])
                && DPSERVER_HOST_URL === substr($this->__GET['location'], 0,
                $len)) {
            return TRUE;
        }
        return (file_exists(DPUNIVERSE_PREFIX_PATH . $this->__GET['location'])
                && is_file(DPUNIVERSE_PREFIX_PATH . $this->__GET['location']))
            || (file_exists(DPUNIVERSE_WWW_PATH . $this->__GET['location'])
                && is_file(DPUNIVERSE_WWW_PATH . $this->__GET['location']));
    }

    /**
     * The user behind the request made a move, handles movement and messages
     *
     * @param   mixed   $from_env   the old environment or FALSE if there's none
     */
    private function _handleNewEnvironment($from_env)
    {
        if (!$from_env) {
            $arrive_msg = sprintf(dptext("%s enters the site.<br />"),
                ucfirst($this->mrUser->getTitle(
                DPUNIVERSE_TITLE_TYPE_DEFINITE)));
            $admin_msg = sprintf(
                dptext("%s enters the site from <span class=\"col2\">%s</span> using <span class=\"col2\">%s</span>.<br />"),
                ucfirst($this->mrUser->getTitle(
                DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                (!isset($this->__SERVER['HTTP_REFERER'])
                || !strlen($this->__SERVER['HTTP_REFERER']) ? '-'
                : $this->__SERVER['HTTP_REFERER']),
                (!isset($this->__SERVER['HTTP_USER_AGENT'])
                || !strlen($this->__SERVER['HTTP_USER_AGENT']) ? '-'
                : $this->__SERVER['HTTP_USER_AGENT']));
            if (!$this->mIsKnownBot) {
                $listeners = get_current_dpuniverse()->getAlertEvent('people_entering');
                if (is_array($listeners)) {
                    $listener_msg = sprintf(
                        dptext("%s enters the site at %s.<br />"),
                        ucfirst($this->mrUser->getTitle(
                        DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                        $this->mrEnvironment->title);
                    $listener_admin_msg = sprintf(
                        dptext("%s enters the site at %s from <span class=\"col2\">%s</span> using <span class=\"col2\">%s</span>.<br />"),
                        ucfirst($this->mrUser->getTitle(
                        DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                        $this->mrEnvironment->title,
                        (!isset($this->__SERVER['HTTP_REFERER'])
                        || !strlen($this->__SERVER['HTTP_REFERER']) ? '-'
                        : $this->__SERVER['HTTP_REFERER']),
                        (!isset($this->__SERVER['HTTP_USER_AGENT'])
                        || !strlen($this->__SERVER['HTTP_USER_AGENT']) ? '-'
                        : $this->__SERVER['HTTP_USER_AGENT']));

                    foreach ($listeners as &$listener) {
                        if ($listener !== $this->mrUser
                                && $listener->getEnvironment() !==
                                $this->mrEnvironment) {
                            $listener->tell(!$listener->isAdmin ? $listener_msg
                            : $listener_admin_msg);
                        }
                    }
                }
            } else {
                $listeners = get_current_dpuniverse()->getAlertEvent(
                    'bots_entering');
                if (is_array($listeners)) {
                    $listener_msg = sprintf(
                        dptext("%s indexes %s.<br />"), ucfirst(
                        $this->mrUser->getTitle(
                        DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                        $this->mrEnvironment->title);
                    foreach ($listeners as &$listener) {
                        if ($listener !== $this->mrUser
                                && $listener->getEnvironment() !==
                                $this->mrEnvironment) {
                            $listener->tell($listener_msg);
                        }
                    }
                }
            }
        } else {
            $from_env->tell(sprintf(dptext("%s leaves to %s.<br />"),
                ucfirst($this->mrUser->getTitle(
                DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                $this->mrEnvironment->getTitle()), $this->mrUser);
            $arrive_msg = $admin_msg = sprintf(
                dptext("%s arrives from %s.<br />"),
                ucfirst($this->mrUser->getTitle(
                DPUNIVERSE_TITLE_TYPE_DEFINITE)), $from_env->getTitle());
        }
        $this->mrUser->moveDpObject($this->mrEnvironment);
        $inv = $this->mrEnvironment->getInventory();
        foreach ($inv as &$ob) {
            if ($ob !== $this->mrUser) {
                $ob->tell(!$ob->isAdmin ? $arrive_msg : $admin_msg);
            }
        }
    }

    /**
     * Handles page requests from users whose environment did not change
     */
    private function _handleReloadEnvironment()
    {
        $this->mrUser->lastActionTime = !isset($this->mrUser->lastActionTime)
            ? new_dp_property(time()) : time();

        if (FALSE !== ($body = $this->mrEnvironment->getAppearance(0, TRUE,
                NULL, $this->mrUser->displayMode))) {
            if (!is_null($this->mrEnvironment->getTemplateFile())) {
                $this->mrUser->tell('<xhtml>' . $body . '</xhtml>');
            } else {
                $this->mrUser->tell('<div id="dppage">' . $body . '</div>');
            }
            if ($type = $this->mrEnvironment->isMovingArea) {
                $this->mrUser->tell('<script type="text/javascript" ' .
                    'src="' . DPUNIVERSE_WWW_URL
                    . 'interface/iutil.js"></script>');
                $this->mrUser->tell('<script type="text/javascript" ' .
                    'src="' . DPUNIVERSE_WWW_URL
                    . 'interface/idrag.js"></script>');
                $containment = $type === 1 ? "containment : 'parent',\n" : '';
                $cssfix = $type == 1 ? '.dpinventory, .dpinventory2'
                    : '.dpinventory';
                $this->mrUser->tell("<script>
function init_drag() {
    if (\$.iDrag == undefined) return;
    \$('div.draggable').DraggableDestroy();
    \$('div.draggable').Draggable({
        handle: 'img.draggable',
        zIndex: 1000,
        ghosting: true,
        opacity: 0.7,{$containment}
        onChange : function() { stopdrag(this) }
    });
    \$('{$cssfix}').css('position', 'relative');
    \$('{$cssfix}').css('overflow', 'hidden');
}
\$(function(){init_drag();});</script>\n");
            }
        }
    }

    /**
     * Handles calls to methods in DutchPIPE objects from users' clients
     */
    private function _handleMethodCall()
    {
        if (!isset($this->__GET['call_object'])
                || !strlen($call_object = $this->__GET['call_object'])) {
            if (!isset($this->__GET['param'])) {
                $this->mrEnvironment->{$this->__GET['method']}();
            } else {
                $this->mrEnvironment->
                    {$this->__GET['method']}($this->__GET['param']);
            }
        }
        else {
            if (FALSE !== ($call_object =
                    $this->mrDpUniverse->findDpObject($call_object))) {
                if (!isset($this->__GET['param'])) {
                    $call_object->{$this->__GET['method']}();
                } else {
                    $call_object->{$this->__GET['method']}
                        ($this->__GET['param']);
                }
            }
        }
    }

    /**
     * Handles insertion of main DutchPIPE elements to standalone pages
     *
     * Communicates with the user's client to insert avatars and object images
     * (the inventory), the message area and login/logout link. Used by
     * standalone pages.
     */
    private function _handleGetDivsCall()
    {
        //echo "getdivs: {$this->__GET['getdivs']}\n";
        $getdivs = explode('#', trim($this->__GET['getdivs'], '#'));
        foreach ($getdivs as $getdiv) {
            //echo "getdiv: $getdiv\n";
            if ($getdiv == 'dpinventory') {
                $this->mrUser->tell($this->mrEnvironment->
                    getAppearanceInventory(0, TRUE, $this->mrUser,
                    $this->mrUser->displayMode));
            } elseif ($getdiv == 'dpmessagearea') {
                $this->mrUser->tell('<div id="dpmessagearea">'
                    . '<div id="dpmessagearea_inner"><div id="messages">'
                    . '<br clear="all" /></div><br clear="all" />'
                    . '<form id="actionform" method="get" onSubmit="return '
                    . 'action_dutchpipe()"><input id="dpaction" type="text" '
                    . 'name="dpaction" value="" size="40" maxlength="255" '
                    . 'style="float: left; margin-top: 0px" '
                    . 'autocomplete="off" /></form></div>'
                    . '<br clear="all" />&#160;</div>');
                $this->mrUser->tell("<script>\$('#dpaction').bind('keydown', "
                    ."bindKeyDown); \$('#dpaction').focus();</script>");
            } elseif ($getdiv == 'dploginout') {
                $login_link = !isset($this->mrUser->isRegistered) ||
                    TRUE !== $this->mrUser->isRegistered
                    ? '<a href="' . DPSERVER_CLIENT_URL . '?location='
                    . DPUNIVERSE_PAGE_PATH. 'login.php" style='
                    . '"padding-left: 4px">' . dptext('Login/register')
                    . '</a>'
                    : '<a href="' . DPSERVER_CLIENT_URL . '?location='
                    . DPUNIVERSE_PAGE_PATH . 'login.php&amp;act=logout" '
                    . 'style="padding-left: 4px">' . dptext('Logout')
                    . '</a>';
                $bottom = dptext('Go to Bottom');
                $this->mrUser->tell('<div id="dploginout">' . sprintf(
                        dptext('Welcome %s'), '<span id="username">'
                        . $this->mrUser->getTitle() . '</span>')
                        . ' <span id="loginlink">'
                        . $login_link . '</span>&#160;&#160;&#160;&#160;'
                        . '<img id="butbottom" src="/images/bottom.gif" '
                        . 'align="absbottom" width="11" height="11" border="0" '
                        . 'alt="' . $bottom . '" title="' . $bottom . '" '
                        . 'onClick="_gel(\'dpaction\').focus(); '
                        . 'scroll(0, 999999)" /></div>');
            }
        }
    }

    /**
     * Handles this user request after we have a user object and environment
     *
     * Checks for waiting messages and actions performed.
     */
    function handleUser()
    {
        $this->mrUser->isAjaxCapable = isset($this->__GET['ajax']);

        /*
         * Skip once if the user has moved and hence the request died. Need to
         * think if this must account for the three other calls below too.
         */
        if (!isset($this->mHasMoved)) {
            $this->_handleMessages();
        }
        $this->_handleAction();
        /* $this->handleMoverror(); */
        $this->_handleNothingTold();
    }

    /**
     * Checks for and handles actions by the current user
     */
    function _handleAction()
    {
        if (isset($this->__GET) && is_array($this->__GET)
                && isset($this->__GET['dpaction'])) {
            $this->mrUser->performAction(htmlspecialchars(
                (string)$this->__GET['dpaction']));
        }
    }

    /**
     * Checks for stored messages for the current user
     */
    function _handleMessages()
    {
        if (FALSE !== ($messages =
                $this->mrDpUniverse->getCurrentDpUserMessages())) {
            /* Tell user all stored messages */
            foreach ($messages as &$message) {
                if (is_null($message[1])
                        || (FALSE !== ($env = $this->mrUser->getEnvironment())
                        && $env === $message[1])) {
                    $this->mrUser->tell($message[0]);
                    $this->mToldSomething = TRUE;
                }
            }
            /* Delete these stored messages */
            $this->mrDpUniverse->clearCurrentDpUserMessages();
        }
    }

    /**
     * Handles sending '1' for AJAX if no other talkback was sent
     */
    function _handleNothingTold()
    {
        if (isset($this->__GET['ajax']) && !isset($this->__GET['method'])) {
            if (FALSE === $this->mToldSomething) {
                $this->mrUser->tell("empty");
            } else {
                echo "---------------------------\n";
            }
        } else {
            echo "---------------------------\n";
        }
    }

    /**
     * Sets the user been told anything this request
     */
    final function setToldSomething()
    {
        $this->mToldSomething = TRUE;
    }

    /**
     * Has the user been told anything this request?
     *
     * @return  boolean TRUE if user was told something, FALSE otherwise
     */
    final function isToldSomething()
    {
        return isset($this->mToldSomething) && TRUE === $this->mToldSomething;
    }

    /**
     * Is this a registered user?
     *
     * @return  boolean TRUE for a registered user, FALSE otherwise
     */
    final function isRegistered()
    {
        return $this->mIsRegistered;
    }

    /**
     * :KLUDGE: Used as a kludge to handle user movements within this request
     */
    final function setHasMoved()
    {
        $this->mHasMoved = TRUE;
    }

    /**
     * Gets the "user agent" reported by the current user's browser
     *
     * @return  mixed   User agent string or FALSE for no agent reported
     */
    function getUserAgent()
    {
        return $this->mUserAgent;
    }
}
