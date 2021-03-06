<?php
/**
 * Provides 'DpUniverse' class to handle a specific 'universe'
 *
 * Defines DpObjects, users, pages, etc. (our 'rules of nature')
 *
 * DutchPIPE version 0.4; PHP version 5
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
 * @version    Subversion: $Id: dpuniverse.php 311 2007-09-03 12:48:09Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        currentdpuserrequest.php, dpserver.php, dpfunctions.php
 */

/* Shows all possible errors */
error_reporting(E_ALL | E_STRICT);

/**
 * The universe object handling the current http user request. Do not access
 * this variable directly, use get_current_dpuniverse() instead.
 */
$grCurrentDpUniverse = NULL;

/**
 * The object responsible for the current chain of execution. Do not access
 * this variable directly, use get_current_dpobject() instead.
 */
$grCurrentDpObject = NULL;

define('_DPUSER_OBJECT', 0);           /* Reference to /std/DpUser.php object */
define('_DPUSER_MESSAGES', 1);         /* Array of strings with messages */
define('_DPUSER_NAME', 2);             /* Name of user behind http request */
define('_DPUSER_COOKIEID', 3);         /* Cookie Id used for authorization */
define('_DPUSER_COOKIEPASS', 4);       /* Cookie Pass used for authorization */
define('_DPUSER_ISREGISTERED', 5);     /* Is it a registered user? */
define('_DPUSER_TIME_LASTREQUEST', 6); /* Last http request UNIX time */
define('_DPUSER_CURRENT_SCRIPTID', 7); /* Script id of current AJAX request */
define('_DPUSER_LAST_SCRIPTID', 8);    /* Script id of last AJAX request */

/**
 * A DutchPIPE universe, handling objects, users, pages, etc. (rules of nature)
 *
 * @package    DutchPIPE
 * @subpackage lib
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
final class DpUniverse
{
    /**
     * @var         array      All objects on the site
     * @access      private
     */
    private $mDpObjects = array();

    /**
     * @var         array      Reset queue, first element will reset first
     * @access      private
     */
    private $mDpObjectResets = array();

    /**
     * The environment of each object on the site in env => ob pairs.
     *
     * @var         array      The environments of objects
     * @access      private
     */
    private $mEnvironments = array();

    /**
     * All user objects on the site + data
     *
     * Each element in the array represents a user. A user is stored in another
     * array, with the elements as defined by the _DPUNIVERSE_USER_ definitions.
     *
     * @var         array      All user objects on the site + data
     * @access      private
     */
    private $mDpUsers = array();

    /**
     * @var         array      All pending timeouts after use of setTimeout()
     * @access      private
     */
    private $mTimeouts = array();

    /**
     * Increasing guest counter used to form unique names for guests, for
     * example "Guest#8'
     *
     * @var         int        Increasing guest counter for unique guest names
     * @access      private
     */
    private $mGuestCnt;

    /**
     * DpObject counter increased by newDpObject, used to generate unique object
     * ids
     *
     * @var         int        Used to generate unique object
     * @access      private
     */
    private $mUniqueDpObjectCnt = 1;

    /**
     * @var         object     The server object that called us
     * @access      private
     */
    private $mrDpServer;

    /**
     * @var         array      Info on the current HTTP user request
     * @access      private
     */
    private $mrCurrentDpUserRequest;

    /**
     * @var         array      The key of the current users' entry in mDpUsers
     * @access      private
     */
    private $mCurrentDpUserKey;

    /**
     * @var         boolean    Do not tell anything to the current http request?
     * @access      private
     */
    private $mNoDirectTell = FALSE;

    /**
     * Error messages for the last new user registration attempt
     *
     * @var         array
     * @access      private
     */
    private $mLastNewUserErrors = array();

    /**
     * UNIX timestamp when next reset in an object will occur
     *
     * @var         integer
     * @access      private
     */
    private $mNextResetTime;

    /**
     * UNIX time stamp when cleanup mechanism was last called
     *
     * @var         array
     * @access      private
     */
    private $mLastCleanUpTime = 0;

    /**
     * Lists of objects listening to certain events
     *
     * @var         array
     * @access      private
     */
    private $mAlertEvents = array();

    /**
     * Constructs this universe based on a universe ini file
     *
     * @param      string    $iniFile    path to settings file for this universe
     */
    function __construct($iniFile = 'dpuniverse-ini.php')
    {
        /* Gets the universe settings */
        require_once($iniFile);

        $this->mGuestCnt = mt_rand(25, 75);
        $this->mLastCleanUpTime = time();

        require_once(DPUNIVERSE_LIB_PATH . 'dpcurrentrequest.php');

        /* These functions will be available for all objects */
        require_once(DPUNIVERSE_LIB_PATH . 'dptext.php');
        require_once(DPUNIVERSE_LIB_PATH . 'dpfunctions.php');
        require_once(DPUNIVERSE_LIB_PATH . 'dptemplates.php');
        require_once(DPUNIVERSE_LIB_PATH . 'dpmbstring_'
            . (!DPSERVER_ENABLE_MBSTRING || !function_exists('mb_strlen')
            ? 'disabled' : 'enabled') . '.php');

        if (!DPUNIVERSE_MDB2_ENABLED) {
            require_once(DPUNIVERSE_LIB_PATH . 'dpdb_mysql.php');
        } else {
            require_once(DPUNIVERSE_LIB_PATH . 'dpdb_mdb2.php');
        }

        // Clean up guest avatar directory
        if (DPUNIVERSE_AVATAR_CUSTOM_ENABLED
                && ($dir = @opendir(DPUNIVERSE_AVATAR_CUSTOM_GUEST_PATH))) {
            while (FALSE !== ($entry = readdir($dir))) {
                if ('.' != $entry && '..' != $entry)
                    @unlink(DPUNIVERSE_AVATAR_CUSTOM_GUEST_PATH . $entry);
            }
        }
    }

    /**
     * Gets an unique suffix to make Guest#x names
     *
     * @return     integer   A unique number, currently a counter
     */
    function getGuestCnt()
    {
        return $this->mGuestCnt++;
    }

    /**
     * Handles user requests passed on from the DutchPIPE server
     *
     * Called each time a user's browser does a normal page or AJAX request.
     * Several variables are passed which represent their corresponding PHP
     * global arrays: $_SERVER, $_COOKIE, etc.
     *
     * @param   array   &$rDpServer    Reference to the server object
     * @param   array   &$rServerVars  User server variables
     * @param   array   &$rSessionVars User session variables
     * @param   array   &$rCookieVars  User cookie variables
     * @param   array   &$rGetVars     User get variables
     * @param   array   &$rPostVars    User post variables
     * @param   array   &$rFilesVars   User files variables
     */
    function handleCurrentDpUserRequest(&$rDpServer = NULL,
            &$rServerVars = NULL, &$rSessionVars = NULL, &$rCookieVars = NULL,
            &$rGetVars = NULL, &$rPostVars = NULL, &$rFilesVars = NULL)
    {
        if (!is_null($rDpServer)) {
            $this->mrDpServer = $rDpServer;
        }

        $GLOBALS['grCurrentDpUniverse'] = &$this;

        /*
         * Because we don't use a 'ticks' system or something similar yet, user
         * user requests are used to handle some generic cyclic calls
         */
        $this->handleLinkdead();
        if (isset($this->mNextResetTime) && $this->mNextResetTime <= time()) {
            $this->handleReset();
        }

        $this->mrCurrentDpUserRequest = new DpCurrentRequest($this,
            $rServerVars, $rSessionVars, $rCookieVars, $rGetVars, $rPostVars,
            $rFilesVars);
        $this->mrCurrentDpUserRequest->handleRequest();

        if (FALSE === $this->mrCurrentDpUserRequest->isRegistered()
                && FALSE === $this->mrCurrentDpUserRequest->getUserAgent()) {
            if (!isset($rGetVars['ajax'])) {
                $this->tellCurrentDpUserRequest('<event><div id="dppage">'
                    . '<![CDATA['
                    . dp_text('Your browser did not report a User Agent string to the server. This is required.<br />')
                    . ']]></div></event>');
            }
            unset($this->mrCurrentDpUserRequest);
            unset($this->mCurrentDpUserKey);
            return;
        }
        if (FALSE === $this->mrCurrentDpUserRequest->isCookieEnabled()) {
            $this->tellCurrentDpUserRequest('2');
            unset($this->mrCurrentDpUserRequest);
            unset($this->mCurrentDpUserKey);
            return;
        }

        if (isset($this->mCurrentDpUserKey)) {
            $user_arr_key = $this->mCurrentDpUserKey;
        } else {
            end($this->mDpUsers);
            $user_arr_key = key($this->mDpUsers);
        }

        $this->mDpUsers[$user_arr_key][_DPUSER_TIME_LASTREQUEST] = time();

        /*
         * scriptids are used to detect if a user has multiple browser windows
         * open. Each initiated dpclient-js.php sets such a random id
         */
        $old_scriptid =
            $this->mDpUsers[$user_arr_key][_DPUSER_CURRENT_SCRIPTID];
        $new_scriptid = is_null($rGetVars) || !isset($rGetVars['ajax'])
            || !isset($rGetVars['scriptid']) || 0 === (int)$rGetVars['scriptid']
            ? FALSE : $rGetVars['scriptid'];

        if (FALSE !== $old_scriptid && FALSE !== $new_scriptid
                && $old_scriptid !== $new_scriptid) {
            $this->mDpUsers[$user_arr_key][_DPUSER_LAST_SCRIPTID] = FALSE;
            $this->mDpUsers[$user_arr_key][_DPUSER_CURRENT_SCRIPTID] = FALSE;
            $this->mDpUsers[$user_arr_key][_DPUSER_OBJECT]->tell(
                'close_window');
        } else {
            $this->mDpUsers[$user_arr_key][_DPUSER_LAST_SCRIPTID] =
                $old_scriptid;
            $this->mDpUsers[$user_arr_key][_DPUSER_CURRENT_SCRIPTID] =
                $new_scriptid;
        }

        /*
         * Because we don't use a 'ticks' system or something similar yet, user
         * user requests are used to handle some generic cyclic calls
         */
        $this->handleTimeouts();

        $this->mrCurrentDpUserRequest->handleUser();

        unset($this->mrCurrentDpUserRequest);
        unset($this->mCurrentDpUserKey);

        if ($this->mLastCleanUpTime < time() - DPUNIVERSE_RESET_CYCLE * 2) {
            $this->handleCleanUps();
            $this->mLastCleanUpTime = time();
        }
    }

    /**
     * Adds a new, given user object to the universe
     *
     * :WARNING: Should normally only be called from lib/dpcurrentrequest.php.
     *
     * @access     private
     * @param      object    &$user        a new DpUser object
     * @param      string    $username     the user's username
     * @param      string    $cookieID     the user's cookie ID
     * @param      string    $cookiePass   the user's cookie password
     * @param      boolean   $isRegistered TRUE for registered user, else FALSE
     */
    function addDpUser(&$user, $username, $cookieId, $cookiePass,
            $isRegistered) {
        $this->mDpUsers[] = array($user, array(), $username, $cookieId,
            $cookiePass, $isRegistered, 0, FALSE, FALSE);
    }

    /**
     * Gets pending messages for the current user request
     *
     * :WARNING: Should normally only be called from lib/dpcurrentrequest.php.
     *
     * @access     private
     * @return     mixed     array with messages of FALSE for no messages
     */
    function &getCurrentDpUserMessages()
    {
        $rval = FALSE;

        if (isset($this->mCurrentDpUserKey)
                && isset($this->mDpUsers[$this->mCurrentDpUserKey])) {
            return $this->mDpUsers[$this->mCurrentDpUserKey][_DPUSER_MESSAGES];
        }

        return $rval;
    }

    /**
     * Clears pending messages for the current user request
     *
     * :WARNING: Should normally only be called from lib/dpcurrentrequest.php.
     *
     * @access     private
     */
    function clearCurrentDpUserMessages()
    {
        $this->mDpUsers[$this->mCurrentDpUserKey][_DPUSER_MESSAGES] = array();
    }

    /**
     * Finds if a user is on this site based on cookie data
     *
     * If found, sets $this->mCurrentDpUserKey to the key to the user info in
     * the universe's user array.
     *
     * :WARNING: Should normally only be called from lib/dpcurrentrequest.php.
     *
     * @access     private
     * @param      string    $cookieID     the user's cookie ID
     * @param      string    $cookiePass   the user's cookie password
     * @return     mixed     array with user info or FALSE if no user found
     */
    function &findCurrentDpUser($cookieid, $cookiepass)
    {
        $rval = FALSE;

        foreach ($this->mDpUsers as $user_arr_key => &$u) {
            if ($u[_DPUSER_COOKIEID] === $cookieid
                    && $u[_DPUSER_COOKIEPASS] === $cookiepass) {
                $this->mCurrentDpUserKey = $user_arr_key;
                return $u;
            }
        }

        return $rval;
    }

    /**
     * Checks if people left the site, throws them out of the universe
     *
     * @access     private
     */
    private function handleLinkdead()
    {
        $cur_time = time();

        /* Time the user's browser should have done a page or AJAX request */
        $linkdeath_time = $cur_time - DPUNIVERSE_LINKDEATH_KICKTIME;

        /*
         * Currently, this function is triggered by a user http request. If
         * there's one user and he leaves, then if the next user enters, say a
         * minute later (this time is defined here), he should not see
         * 'X leaves the site.'
         */
        $showmsg_time = $linkdeath_time - DPUNIVERSE_LINKDEATH_SHOWMSGTIME;

        /*
         * Bots may be visible a bit longer, since they don't have Javacript and
         * sometimes do a lot of requests. With a short 'kick' time, users would
         * get a lot of 'Bot enters the site' and 'Bot leaves the site'
         * messages.
         */
        $botkick_time = $cur_time - DPUNIVERSE_BOT_KICKTIME;

        $showbot_time = $botkick_time - DPUNIVERSE_LINKDEATH_SHOWBOTTIME;

        foreach ($this->mDpUsers as $i => &$u) {
            /*
             * Throw out people who lost connection or browsed elsewhere. Need
             * to think this one out (move them to a void, etc.), something
             * simple for now.
             */
            $lastrequest_time = $u[_DPUSER_TIME_LASTREQUEST];
            $ajax_capable = (isset($u[_DPUSER_OBJECT]->isAjaxCapable)
                && TRUE === $u[_DPUSER_OBJECT]->isAjaxCapable)
                || !empty($u[_DPUSER_OBJECT]->browseAvatarCustom);

            /* The "linkdeath" check */
            if (($ajax_capable && $lastrequest_time < $linkdeath_time
                    && (empty($u[_DPUSER_OBJECT]->browseAvatarCustom)
                    || $lastrequest_time < $cur_time
                    - DPSERVER_OBJECT_IMAGE_CUSTOM_MAX_BROWSETIME))
                    || (!$ajax_capable && $lastrequest_time < $botkick_time)) {
                /*
                 * This method is called before an instance of
                 * CurrentDpUserRequest is created and the current http request
                 * is handled. However, the tell() functions operate on the
                 * current request. Therefore, any sound made should be stored
                 * for the next cycle, otherwise the people get wrong messages.
                 * tell() in the User class checks for this variable:
                 */
                $this->mNoDirectTell = TRUE;

                if (FALSE !== ($env =
                        $u[_DPUSER_OBJECT]->getEnvironment())) {
                    if (($ajax_capable && $lastrequest_time > $showmsg_time)
                            || (!$ajax_capable
                            && $lastrequest_time > $showbot_time)) {
                        /* Drop stuff, tell people on the page the user left */
                        $u[_DPUSER_OBJECT]->actionDrop(dp_text('drop'),
                            dp_text('all'));
                        $env->tell($left_msg = sprintf(
                            dp_text('%s left the site.<br />'),
                            ucfirst($u[_DPUSER_OBJECT]->getTitle(
                            DPUNIVERSE_TITLE_TYPE_DEFINITE))),
                            $u[_DPUSER_OBJECT]);
                        if (!$u[_DPUSER_OBJECT]->isKnownBot) {
                            $listeners = get_current_dpuniverse()->
                                getAlertEvent('people_leaving');
                            if (is_array($listeners)) {
                                $listener_msg = ' ' . sprintf(
                                    dp_text("<span class=\"col2\">%s</span> %s left the site at %s.<br />"),
                                    strftime(dp_text('%H:%M')),
                                    ucfirst($u[_DPUSER_OBJECT]->getTitle(
                                    DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                                    $env->title);
                                foreach ($listeners as &$listener) {
                                    if ($listener->getEnvironment() !== $env) {
                                        $listener->tell($listener_msg);
                                    }
                                }
                            }
                        }
                    } else {
                        /* Drop all silently */
                        $u[_DPUSER_OBJECT]->actionDrop(dp_text('drop'),
                            dp_text('all'), TRUE);
                    }
                }

                /* Keeps track of all unique user agents */
                $this->saveUserAgent($u[_DPUSER_OBJECT]);

                /* Remove the object */
                $this->mDpUsers[$i][_DPUSER_OBJECT]->removeDpObject();

                $this->mNoDirectTell = FALSE;
            }
        }
    }

    /**
     * Calls '__reset' in each object every DPUNIVERSE_RESET_CYCLE seconds
     *
     * @access     private
     */
    private function handleReset()
    {
        /* Perform a limited number of resets per cycle */
        $max_resets = DPUNIVERSE_MAX_RESETS;
        $max_loop = 100;

        reset($this->mDpObjectResets);
        /* Pick up where we were in the reset array */
        while (($ob = current($this->mDpObjectResets)) && $max_resets--
                 && $max_loop--) {
            /* Checks if the current object ready to reset */
            if ($ob->resetTime > time()) {
                $this->mNextResetTime = $ob->resetTime;
                return;
            }
            /* Resets the object, sets next reset time */
            $ob->__reset();
            $ob->resetTime = time() + DPUNIVERSE_RESET_CYCLE;
            $this->mDpObjectResets[] = $ob;
            array_splice($this->mDpObjectResets, 0, 1);
            reset($this->mDpObjectResets);
            $this->mNextResetTime = current($this->mDpObjectResets)->resetTime;
        }
    }

    /**
     * Optionally uses experimental PHP runkit module to catch errors in code
     */
     /*
    function handleRunkit()
    {
        if (FALSE !== DPUNIVERSE_RUNKIT) {
            if (FALSE === @runkit_lint_file(DPUNIVERSE_PREFIX_PATH
                    . $this->__GET['location'])) {
                if ($this->mrUser->getEnvironment()) {
                    $this->mrEnvironment = $this->mrUser->getEnvironment();
                }
                $this->__GET['location'] =
                    FALSE === is_null($this->mrEnvironment)
                    ? $this->mrEnvironment->location
                    : DPUNIVERSE_PAGE_PATH . 'index.php';
                $this->mrUser->tell('<location>' . $__GET['location']
                    . '</location>');
                $this->mMoveError = dp_text('You notice a disruptance.<br />');
            }
        }
    }
    */

    /**
     * Handles delayed function calls requested by objects in the universe
     *
     * @access     private
     */
    private function handleTimeouts()
    {
        while (sizeof($this->mTimeouts)) {
            foreach ($this->mTimeouts as $i => $to) {
                if (isset($this->mTimeouts[$i][0]->isRemoved)
                        && TRUE === $this->mTimeouts[$i][0]->isRemoved) {
                    unset($this->mTimeouts[$i][0]);
                    unset($this->mTimeouts[$i]);
                    break;
                }
                $cur_time = time();
                if ($this->mTimeouts[$i][2] <= $cur_time) {
                    $method = $this->mTimeouts[$i][1];
                    if (!isset($this->mTimeouts[$i][3])) {
                        $this->mTimeouts[$i][0]->$method();
                    } else {
                        call_user_func_array(array(&$this->mTimeouts[$i][0],
                            $method), $this->mTimeouts[$i][3]);
                    }
                    unset($this->mTimeouts[$i][0]);
                    unset($this->mTimeouts[$i]);
                    break;
                }
                break 2;
            }
        }
    }

    /**
     * Handles removal of unused object instances
     *
     * @access     private
     */
    private function handleCleanUps()
    {
        echo "HandleCleanUps called\n";
        foreach ($this->mDpObjects as $i => &$ob) {
            if (!$ob->getEnvironment()
                    && $ob->lastEventTime < time() - DPUNIVERSE_RESET_CYCLE) {
                echo "calling handleCleanUp in {$ob->title}\n";
                $ob->handleCleanUp();
            }
        }
    }

    /**
     * Stores user agent information in the database
     *
     * Experimental. Used to catch search bots.
     *
     * @access     private
     * @param      string    &$user      user object
     */
    private function saveUserAgent(&$user)
    {
        $agent = !isset($user->_SERVER['HTTP_USER_AGENT'])
            || 0 === dp_strlen($user->_SERVER['HTTP_USER_AGENT'])
            ? '[Undefined]'
            : $user->_SERVER['HTTP_USER_AGENT'];

        if (isset($user->isAjaxCapable) && TRUE === $user->isAjaxCapable) {
            $result = dp_db_query('SELECT userAgentId from UserAgents '
                . 'WHERE userAgentString=' . dp_db_quote($agent, 'text'));
            if (FALSE === $result || !dp_db_num_rows($result)) {
                if ($next_id = dp_db_next_id('UserAgents', 'userAgentId')) {
                    dp_db_exec('INSERT INTO UserAgents '
                        . '(userAgentId,userAgentString) VALUES ('
                        . dp_db_quote($next_id, 'integer') . ','
                        . dp_db_quote($agent, 'text') . ')');
                }
            }
            dp_db_free($result);
        } else {
            $remote_address = !isset($user->_SERVER['REMOTE_ADDR'])
                || 0 === dp_strlen($user->_SERVER['REMOTE_ADDR'])
                ? '[Undefined]'
                : $user->_SERVER['REMOTE_ADDR'];
            $result = dp_db_query('SELECT userAgentId from UserAgents '
                . 'WHERE userAgentString=' . dp_db_quote($agent, 'text')
                . ' AND userAgentRemoteAddress='
                . dp_db_quote($remote_address, 'text'));
            if (FALSE === $result || !dp_db_num_rows($result)) {
                if ($next_id = dp_db_next_id('UserAgents', 'userAgentId')) {
                    dp_db_exec('INSERT INTO UserAgents '
                        . '(userAgentId,userAgentString,userAgentRemoteAddress)'
                        . ' VALUES (' . dp_db_quote($next_id, 'integer') . ','
                        . dp_db_quote($agent, 'text') . ','
                        . dp_db_quote($remote_address, 'text') . ')');
                }
            }
            dp_db_free($result);
        }
    }

    /**
     * Stores a message for a user while we wait for another http request
     *
     * Called from tell in DpUser.php
     *
     * :WARNING: This method should normally only be called the tell method in
     * DpUser.php.
     *
     * @access     private
     * @param      object    $user        recipient user of message
     * @param      string    $data        message string
     * @param      object    $binded_env  optional binded environment
     * @see        DpObject::tell()
     *
     */
    function storeTell(&$user, $data, &$binded_env = NULL)
    {
        foreach ($this->mDpUsers as $i => &$u) {
            if ($u[_DPUSER_OBJECT] === $user) {
                $this->mDpUsers[$i][1][] = array($data, $binded_env);
            }
        }
    }

    /**
     * Tells something back to the currently connected user client
     *
     * :WARNING: This method should normally only be called from DpUser.php.
     *
     * @access     private
     * @param      string    $talkback   XML string
     */
    function tellCurrentDpUserRequest($talkback)
    {
        // echo "Talkback: " . htmlentities($talkback) . "\n";
        $this->mrDpServer->tellCurrentDpUserRequest($talkback);
        $this->mrCurrentDpUserRequest->setToldSomething();
    }

    /**
     * Gets db row key of a random CAPTCHA code for the user registration page
     *
     * Used to seperate software robots from real people during registration.
     * Codes aren't generated on the fly and as such not really random because
     * the CPU time penalty is too high. A directory with pre-generated codes
     * exists and a database table with info. A cronjob makes one every hour and
     * replaces the oldest one in the database. Then we obtain a random entry
     * here. This should do it for now, but your milleague might vary and the
     * situation might change as spambots get smarter.
     *
     * @return     int       CAPTCHA database row key
     */
    function getRandCaptcha()
    {
        $result = dp_db_query('SELECT captchaId FROM Captcha');

        if (FALSE === $result || !($num_rows = dp_db_num_rows($result))) {
            return FALSE;
        }

        $rval = dp_db_fetch_one($result, mt_rand(0, $num_rows - 1), 0);
        dp_db_free($result);
        return $rval;
    }

    /**
     * Creates a new object in the universe
     *
     * You MUST call this function to create new objects in a DutchPIPE
     * universe, don't use the 'new' construct directly.
     *
     * The object can have a unique location, given with $pathname, or just be
     * based on $pathname, with a sublocation handling multiple objects. For
     * example, the URL in uour browser contains the following bit for the
     * DutchPIPE "about" page: location=/page/about.php
     *
     * It is a unique page with a unique location. The manual however is not
     * based on many unique locations, but just one object which spawns pages
     * based on the sublocation given in the URL:
     * location=/page/manual.php&sublocation=index.html
     *
     * @param      string    $pathname     path to code from universe base path
     * @param      string    $sublocation  optional sublocation
     * @return     object    The newly created object
     */
    function &newDpObject($pathname, $sublocation = FALSE)
    {
        if (!$pathname) {
            $pathname = DPUNIVERSE_PAGE_PATH . 'index.php';
        }

        echo "Making: $pathname, $sublocation\n";

        if (dp_strlen($pathname) >= 7 && dp_substr($pathname, 0, 7)
                == 'http://') {
            if (($len = dp_strlen(DPSERVER_HOST_URL)) >= dp_strlen($pathname)
                    || DPSERVER_HOST_URL !== dp_substr($pathname, 0, $len)) {
                echo dp_text("Illegal object requested!\n");
                $rval = FALSE;
                return $rval;
            }
        }

        $unique_id = $this->mUniqueDpObjectCnt;
        $this->mUniqueDpObjectCnt++;

        if ($pathname && (dp_substr($pathname, 0, 1) != '/'
                || ((FALSE === dp_strpos($pathname, '://'))
                && (dp_strlen($pathname) < 4
                || dp_substr($pathname, - 4) != '.php')))) {
            require_once(DPUNIVERSE_PREFIX_PATH . DPUNIVERSE_STD_PATH
                . 'DpPage.php');
            $ob = new DpPage($unique_id, time() + DPUNIVERSE_RESET_CYCLE,
                $pathname, FALSE);
            $ob->setTitle($pathname);
            $ob->isStandalone = new_dp_property(TRUE);
            if (FALSE === dp_strpos($pathname, '://')) {
                $ob->setBody($pathname, 'file');
                $ob->setNavigationTrail(array(DPUNIVERSE_NAVLOGO, '/'));
            }
        } else {
            require_once(DPUNIVERSE_PREFIX_PATH . $pathname);
            $classname =  explode("/", $pathname);
            $classname = ucfirst(!dp_strlen($classname[sizeof($classname) - 1])
                ? 'index'
                : dp_substr($classname[sizeof($classname) - 1], 0, -4));
            $ob = new $classname($unique_id, time() + DPUNIVERSE_RESET_CYCLE,
                $pathname, $sublocation);
        }
        if (empty($ob)) {
            $rval = FALSE;
            return $rval;
        }

        $this->__GET['location'] = $pathname;
        if (FALSE !== $sublocation) {
            $this->__GET['sublocation'] = $sublocation;
        }

        $this->mDpObjects[] =& $ob;
        $this->mDpObjectResets[] =& $ob;
        if (!isset($this->mNextResetTime)) {
            $this->mNextResetTime = $ob->resetTime;
        }

        $ob->__construct2();

        echo sprintf(dp_text("Made new object %s\n"), $pathname);

        return $ob;
    }

    /**
     * Moves the object to another environment
     *
     * This method only handles the internal storage of environments.
     * Most functionality can be found in  moveDpObject in DpObject.php.
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @access     private
     * @param      object  &$rItem      object to move
     * @param      mixed   &$rDest      object to move into to
     */
    function moveDpObject(&$rItem, &$rDest)
    {
        foreach ($this->mEnvironments as $i => &$pair) {
            if ($pair[0] === $rItem) {
                unset($this->mEnvironments[$i]);
                break;
                $this->mEnvironments[$i][1] =& $rDest;
                return;
            }
        }

        $this->mEnvironments[] = array(&$rItem, &$rDest);
    }

    /**
     * Removes this object
     *
     * The object is destroyed and no longer part of the universe.
     * This method only handles the internal storage of objects.
     * Most functionality can be found in removeDpObject in DpObject.php.
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @access     private
     * @param      object  &$rTarget    object to remove
     */
    function removeDpObject(&$rTarget)
    {
        global $grCurrentDpObject;

        foreach ($this->mDpUsers as $i => &$u) {
            if ($u[_DPUSER_OBJECT] === $rTarget) {
                $del_user = $i;
                break;
            }
        }

        $del_envs = array();
        foreach ($this->mEnvironments as $i => &$env) {
            if ($env[0] === $rTarget) {
                $del_env = $i;
            }
            if ($env[1] === $rTarget) {
                $del_envs[] = $i;
            }
        }

        foreach ($this->mDpObjects as $i => &$ob) {
            if ($ob === $rTarget) {
                $del_obj = $i;
                break;
            }
        }

        foreach ($this->mDpObjectResets as $i => &$ob) {
            if ($ob === $rTarget) {
                $del_reset = $i;
                break;
            }
        }

        $del_timeouts = array();
        foreach ($this->mTimeouts as $i => &$ob) {
            if ($ob[0] === $rTarget) {
                $del_timeouts[] = $i;
            }
        }

        $del_events = array();
        foreach ($this->mAlertEvents as $event => &$listeners) {
            foreach ($listeners as $i => &$ob) {
                if ($ob === $rTarget) {
                    $del_events[] = array($event, $i);
                }
            }
        }
        if (isset($del_user)) {
            unset($this->mDpUsers[$del_user][_DPUSER_OBJECT]);
            unset($this->mDpUsers[$del_user]);
        }
        if (isset($del_env)) {
            unset($this->mEnvironments[$del_env][0]);
            unset($this->mEnvironments[$del_env]);
        }
        foreach ($del_envs as $del_env) {
            unset($this->mEnvironments[$del_env][1]);
            $this->mEnvironments[$del_env][0]->removeDpObject();
        }
        if (isset($del_obj)) {
            unset($this->mDpObjects[$del_obj]);
        }
        if (isset($del_reset)) {
            unset($this->mDpObjectResets[$del_reset]);
        }
        foreach ($del_timeouts as $del_timeout) {
            unset($this->mTimeouts[$del_timeout][0]);
            unset($this->mTimeouts[$del_timeout]);
        }
        foreach ($del_events as $event => $i) {
            unset($this->mAlertEvents[$event][$i]);
        }
        unset($rTarget);
    }

    /**
     * Finds an object in the universe with the given unique id
     *
     * @param      string    $unique_id  the object's unique string id
     * @return     mixed     Object reference if found, FALSE otherwise
     */
    function &findDpObject($unique_id)
    {
        $rval = FALSE;

        foreach ($this->mDpObjects as $i => &$ob) {
            if ($unique_id === $ob->getUniqueId()) {
                return $ob;
            }
        }

        return $rval;
    }

    /**
     * Finds or makes an object in the universe with the given pathname
     *
     * If an object with the given pathname exists, a reference to that object
     * is returned. Otherwise a new instance of the class found at $pathname
     * is created and returned.
     *
     * The object can have a unique location, given with $pathname, or just be
     * based on $pathname, with a sublocation handling multiple objects. For
     * example, the URL in uour browser contains the following bit for the
     * DutchPIPE "about" page: location=/page/about.php
     *
     * It is a unique page with a unique location. The manual however is not
     * based on many unique locations, but just one object which spawns pages
     * based on the sublocation given in the URL:
     * location=/page/manual.php&sublocation=index.html
     *
     *
     * @param      string    $pathname     a path within dpuniverse/
     * @param      string    $sublocation  optional sublocation
     * @return     object    Reference to instance of $pathname
     * @see        newDpObject
     */
    function &getDpObject($pathname, $sublocation = FALSE)
    {
        if (!$pathname) {
            $pathname = DPUNIVERSE_PAGE_PATH . 'index.php';
        }

        if (FALSE === $sublocation) {
            foreach ($this->mDpObjects as $i => &$ob) {
                if ($pathname === $ob->location) {
                    //echo dp_text("getDpObject(): returning existing object with location %s, no sublocation\n",
                    //    $pathname);
                    return $ob;
                }
            }
        } else {
            foreach ($this->mDpObjects as $i => &$ob) {
                if ($pathname === $ob->location
                        && $sublocation === $ob->sublocation) {
                    //echo dp_text("getDpObject(): returning existing object with location %s, sublocation %s\n",
                    //    $pathname, $sublocation);
                    return $ob;
                }
            }
        }

        echo FALSE == $sublocation
            ? dp_text("getDpObject(): returning new object for location %s, no sublocation\n",
                $pathname)
            : dp_text("getDpObject(): returning new object for location %s, sublocation %s\n",
                $pathname, $sublocation);
        return $this->newDpObject($pathname, $sublocation);
    }

    /**
     * Gets the object reference to the environment of this object
     *
     * Don't call this method here, call it in objects in the universe instead,
     * for instance $user->getEnvironment().
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @access     private
     * @param      object    &$ob        object we want to know environment of
     * @return     mixed     object reference or FALSE for no environment
     */
    function &getEnvironment(&$ob)
    {
        $env = FALSE;

        foreach ($this->mEnvironments as &$pair) {
            if ($pair[0] === $ob) {
                return $pair[1];
            }
        }

        return $env;
    }

    /**
     * Gets an array with object references to all objects in our inventory
     *
     * If this object contains no other objects, an empty array is returned.
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @access     private
     * @param      object    &$ob        object we want to know inventory of
     * @return     array     object references to objects in our inventory
     */
    function &getInventory(&$ob)
    {
        $inv = array();

        foreach ($this->mEnvironments as &$pair) {
            if ($pair[1] === $ob) {
                $inv[] =& $pair[0];
            }
        }

        return $inv;
    }

    /**
     * Checks if an object is present
     *
     * Search in the inventory of an object, as specified in $where, for another
     * object, as specified in $what. If $what is a string the objects searched
     * are checked for returning TRUE on the call isId($what).
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @access      private
     * @param       string|object   $what   object to search for
     * @param       object          &$where object to search in
     * @return      object|boolean  the found object or FALSE if not found
     * @see         DpObject::getEnvironment(), DpObject::getInventory()
     */
    function &isPresent($what, &$where)
    {
        $inv = $this->getInventory($where);
        $rval = FALSE;
        if (sizeof($inv)) {
            if (is_string($what) && dp_strlen($what = trim($what))) {
                $what = dp_strtolower($what);
                $nr = 1;
                if (FALSE !== ($pos = dp_strrpos($what, ' '))) {
                    $nr = dp_substr($what, $pos + 1);
                    if (FALSE === is_whole_number($nr)) {
                        $nr = 1;
                    }
                    else {
                        $nr = (int)$nr;
                        $what = trim(dp_substr($what, 0, $pos));
                    }
                }
                foreach ($inv as &$ob) {
                    if ($ob->isId($what) && (0 === --$nr)) {
                        return $ob;
                    }
                }
            }
            elseif (is_object($what)) {
                foreach ($inv as &$ob) {
                    if ($ob === $what) {
                        return $ob;
                    }
                }
            }
        }

        return $rval;
    }

    /**
     * Gets the current user connected to the server
     *
     * If a user page or AJAX request caused the current chain of execution that
     * caused this function to be called, that user object is returned.
     * Otherwise FALSE is returned. For example, if the chain of execution if
     * caused by a setTimeout, this will return FALSE.
     *
     * :WARNING: Use the function get_current_dpuser() instead of calling this
     * method.
     *
     * @access     private
     * @return     object    Reference to user object, FALSE for no current user
     */
    function &getCurrentDpUser()
    {
        $rval = !isset($this->mrCurrentDpUserRequest) ? FALSE
            : $this->mrCurrentDpUserRequest->getUser();

        return $rval;
    }

    /**
     * Finds the user with the given user name or id.
     *
     * @param       string   $userName  user name or id of player
     * @return      object|boolean      the found player or FALSE if not found
     */
    function &findUser($userName)
    {
        $rval = FALSE;

        foreach ($this->mDpUsers as &$u) {
            if ($u[_DPUSER_OBJECT]->isId($userName)) {
                return $u[_DPUSER_OBJECT];
            }
        }

        return $rval;
    }

    /**
     * Gets an array with user object references of all users on this site
     *
     * @return     array     user object references of all users
     */
    function &getUsers()
    {
        $users = array();

        /*
         * :TODO: Since this will be used a lot, keep a seperate copy instead of
         * constructing this array each time
         */
        foreach ($this->mDpUsers as &$u) {
            $users[] =& $u[_DPUSER_OBJECT];
        }

        return $users;
    }

    /**
     * Gets the number of users on this site
     *
     * @return     int       number of users on this site
     */
    function getNrOfUsers()
    {
        return sizeof($this->mDpUsers);
    }


    /**
     * Calls a given method in an object after the given number of seconds
     *
     * Use this to perform delayed method calls in the given object. Note that
     * functions such as get_current_dpuser can be totally different when the
     * method is called. Also note that the actual delay is not exact science.
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @access     private
     * @param      object    &$ob        reference to object to call method in
     * @param      string    $method     name of method to call
     * @param      mixed     $arg1, $arg2, ...  optional arguments for method
     * @param      int d     $secs       delay in seconds
     */
    function setTimeout(&$ob, $method, $secs)
    {
        if (is_object($ob) && method_exists($ob, $method) && $secs > 0) {
            $timeout_info = array(&$ob, $method, time() + $secs);
            if (func_num_args() > 3) {
                $args = func_get_args();
                $tmp = array_slice($args, 3);
                $timeout_info[] = $tmp;
            }
            $this->mTimeouts[] =& $timeout_info;
        }
    }

    /**
     * Gets an array with information about the universe
     *
     * The following array is returned:
     *
     * array(
     *     'memory_usage'       : <int Universe memory usage in bytes>
     *     'nr_of_objects'      : <int Number of objects in the universe>
     *     'nr_of_users'        : <int Number of users in the universe>
     *     'nr_of_environments' : <int Number of environments in the universe>
     *     'nr_of_timeouts'     : <int Number of "timeouts" in the universe>
     * );
     *
     * @return     array     universe information
     */
    function getUniverseInfo()
    {
        /*
        echo "Saving...\n";
        $serialized_universe = serialize($this);
        $fp = fopen('/tmp/serialized_universe', 'w');
        fwrite($fp, $serialized_universe);
        fclose($fp);
        */
        $arr = array(
            'memory_usage' => memory_get_usage(),
            'nr_of_objects' => sizeof($this->mDpObjects),
            'nr_of_users' => sizeof($this->mDpUsers),
            'nr_of_environments' => sizeof($this->mEnvironments),
            'nr_of_timeouts' => sizeof($this->mTimeouts));

        return $arr;
    }

    /**
     * Attempts to login the given user object of a guest as a registered user
     *
     * @param      object    &$user      user to validate
     * @return     boolean   TRUE for succesful login, FALSE otherwise
     */
    function validateExisting(&$user)
    {
        $this->mLastNewUserErrors = array();
        if (!isset($user->_GET['username'])
                || 0 === dp_strlen($user->_GET['username'])) {
            $this->mLastNewUserErrors[] = '<li>'
                . dp_text('No username was given') . '</li>';
        }
        if (0 === sizeof($this->mLastNewUserErrors)
                && $user->_GET['username'] === $user->getTitle()) {
            $this->mLastNewUserErrors[] = '<li>'
                . sprintf(dp_text('You are already logged in as %s'),
                $user->getTitle()) . '</li>';
        } elseif (!isset($user->_GET['password'])
                || 0 === dp_strlen($user->_GET['password'])) {
            $this->mLastNewUserErrors[] = '<li>'
                . dp_text('No password was given') . '</li>';
        }
        if (0 === sizeof($this->mLastNewUserErrors)) {
            $username = dp_strtolower($user->_GET['username']);
            $result = dp_db_query('SELECT userUsername, userPassword, '
                . 'userCookieId, userCookiePassword FROM Users WHERE '
                . 'userUsernameLower=' . dp_db_quote($username, 'text'));
            if (FALSE === $result || !($row = dp_db_fetch_row($result))) {
                $this->mLastNewUserErrors[] =
                    '<li>' . dp_text('That username doesn\'t exist') . '</li>';
            } else {
                if ($row[1] !== $user->_GET['password']) {
                    $this->mLastNewUserErrors[] = '<li>'
                        . dp_text('Invalid password') . '</li>';
                }
            }
            dp_db_free($result);
        }

        if (sizeof($this->mLastNewUserErrors)) {
            $user->tell('<window styleclass="dpwindow_error"><h1>'
                . dp_text('Invalid login') . '</h1><br /><ul>'
                . implode('', $this->mLastNewUserErrors) . '</ul></window>');
            return FALSE;
        }

        $username = $row[0];
        $cookie_id = $row[2];
        $cookie_pass = $row[3];
        $user->tell('<cookie>removeguest</cookie>');
        $user->_COOKIE[DPSERVER_COOKIE_NAME] = "$cookie_id;$cookie_pass";
        $user->tell('<cookie>' . $user->_COOKIE[DPSERVER_COOKIE_NAME]
            . '</cookie>');
        $user->_GET['username'] = $username;
        $user->addId($user->_GET['username']);
        $user->addId(dp_strtolower($user->_GET['username']));
        $user->setTitle(ucfirst($user->_GET['username']));
        $user->isRegistered = TRUE;
        if ($user === get_current_dpuser()) {
            $user->titleImgWidth = NULL;
            $user->titleImgHeight = NULL;
            $row = $this->mrCurrentDpUserRequest->findAndInitRegisteredDpUser(
                $cookie_id, $cookie_pass);
            $this->mrCurrentDpUserRequest->initDpUser();
        }

        /* :TODO: Move admin flag to user table in db */
        if (in_array($user->_GET['username'],
                explode('#', DPUNIVERSE_ADMINISTRATORS))) {
            $user->isAdmin = TRUE;
        }
        foreach ($this->mDpUsers as $user_nr => &$u) {
            if ($u[0] === $user) {
                $this->mDpUsers[$user_nr][2] = $user->_GET['username'];
                $this->mDpUsers[$user_nr][3] = $cookie_id;
                $this->mDpUsers[$user_nr][4] = $cookie_pass;
                $this->mDpUsers[$user_nr][5] = 1;
            }
        }
        $user->tell('<changeDpElement id="username">'
            . $user->_GET['username'] . '</changeDpElement>');

        $user->tell(array('abstract' => '<changeDpElement id="'
            . $user->getUniqueId() . '"><b>'
            . $user->getAppearance(1, FALSE) . '</b></changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '"><b>'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</b></changeDpElement>'));
        $user->tell('<changeDpElement id="loginlink"><a href="'
            . DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
            . 'login.php&amp;act=logout" style="padding-left: 4px">'
            . dp_text('Logout') . '</a></changeDpElement>');
        $user->getEnvironment()->tell(array(
            'abstract' => '<changeDpElement id="' . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</changeDpElement>'), $user);
        $user->tell('<window><h1>' . dp_text('Welcome back') . '</h1><br />'
            . sprintf(dp_text('You are now logged in as: <b>%s</b>'),
            $user->getTitle()) . '</window>');

        return TRUE;
    }

    /**
     * Logs out a given registered user and turns the user into a guest
     *
     * @param      object    &$user      user to logout
     */
    function logoutUser(&$user)
    {
        $username = sprintf(dp_text('Guest#%d'), $this->getGuestCnt());
        $cookie_id = make_random_id();
        $cookie_pass = make_random_id();
        $oldtitle = $user->getTitle();
        $user->tell('<cookie>removeregistered</cookie>');
        $user->_COOKIE[DPSERVER_COOKIE_NAME] = "$cookie_id;$cookie_pass";
        $this->tellCurrentDpUserRequest("Set-Login: "
            . $user->_COOKIE[DPSERVER_COOKIE_NAME]);
        $user->_GET['username'] = $username;
        $user->removeId($oldtitle);
        $user->addId($username);
        $user->setTitle(ucfirst($username));
        if ($user->avatarCustom) {
            $user->avatarCustom = NULL;
            $user->titleImgWidth = NULL;
            $user->titleImgHeight = NULL;
            $user->avatarNr = $this->getRandAvatarNr();
            $user->titleImg = DPUNIVERSE_AVATAR_STD_URL . 'user'
                . $user->avatarNr . '.gif';
            $user->body = '<img src="' . DPUNIVERSE_AVATAR_STD_URL
                . 'user' . $user->avatarNr . '_body.gif" border="0" '
                . 'alt="" align="left" style="margin-right: 15px" />'
                . dp_text('A user.') . '<br />';
        }
        $user->isRegistered = FALSE;
        $user->isAdmin = FALSE;
        if ($user === get_current_dpuser()) {
            $this->mrCurrentDpUserRequest->setUsername($username);
        }

        foreach ($this->mDpUsers as $user_nr => &$u) {
            if ($u[0] === $user) {
                $this->mDpUsers[$user_nr][2] = $username;
                $this->mDpUsers[$user_nr][3] = $cookie_id;
                $this->mDpUsers[$user_nr][4] = $cookie_pass;
                $this->mDpUsers[$user_nr][5] = 0;
            }
        }
        $this->mrCurrentDpUserRequest->setHasMoved();
        $this->mNoDirectTell = TRUE;
        $user->tell('<window><h1>' . sprintf(dp_text('Logged out %s'),
            $oldtitle) . '</h1><br />' . dp_text('See you later!') . '<br />'
            . dp_text('You are now: <b>%s</b>', $user->getTitle())
            . '</b></window>');
        $this->mNoDirectTell = FALSE;
        $user->tell('<location>' . DPUNIVERSE_PAGE_PATH
            . 'login.php</location>');

        $user->getEnvironment()->tell(array(
            'abstract' => '<changeDpElement id="' . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</changeDpElement>'), $user);
    }

    /**
     * Attempts the first step in registering a new user, throws CAPTCHA
     *
     * @param      object    &$user      user to register
     * @return     boolean   TRUE to continue to CAPTCHA, FALSE for errors
     */
    function validateNewUser(&$user)
    {
        if (FALSE === $this->validLoginInfo($user->_GET['username'],
                $user->_GET['password'], $user->_GET['password2'])) {
            $user->tell('<window styleclass="dpwindow_error"><h1>'
                . dp_text('Invalid registration') . '</h1><br />'
                . dp_text('Please correct the following errors:') . '<ul>'
                . implode('', $this->mLastNewUserErrors) . '</ul></window>');
            return FALSE;
        } else {
            if (!isset($user->_GET['givencode'])) {
                if (FALSE === ($captcha_id = $this->getRandCaptcha())) {
                    return TRUE;
                }
                $user->tell('<window><form method="post" '
                    . 'onsubmit="send_captcha(' . $captcha_id
                    . '); return false"><div align="center">'
                    . '<img id="captchaimage" src="' . DPSERVER_CLIENT_DIR
                    . 'dpcaptcha.php?captcha_id='
                    . $captcha_id . '" border="0" alt="" /></div>'
                    . '<br clear="all" />'
                    . dp_text('To complete registration, please enter the code you see above:')
                    . '<br /><br /><div align="center" '
                    . 'style="margin-bottom: 5px"><input id="givencode" '
                    . 'type="text" size="6" maxlength="6" value="" /> '
                    . '<input type="submit" value="'
                    . dp_text('OK') . '" /></div><br />'
                    . dp_text('This system is used to filter software robots
from registrations submitted by individuals. If you are unable to validate the
above code, please <a href="mailto:registration@dutchpipe.org">mail us</a>
to complete registration.') . '</form></window>');
                return FALSE;
            }
            return TRUE;
        }
    }

    /**
     * Attempts the second step in registering a new user, validates CAPTCHA
     *
     * @param      object    &$user      user to register
     */
    function validateCaptcha(&$user)
    {
        if (FALSE === $this->validateNewUser($user)) {
            return;
        }
        if (!isset($user->_GET['captcha_id'])
                || !isset($user->_GET['givencode']) || FALSE ===
                $this->validateCaptcha2($user->_GET['captcha_id'],
                $user->_GET['givencode'])) {
            if (NULL === ($captcha_attempts = $user->captchaAttempts)
                    || $captcha_attempts < 2) {
                $user->captchaAttempts = new_dp_property(
                    NULL === $captcha_attempts ? 1 : $captcha_attempts + 1);
                return;
            }
            unset($user->captchaAttempts);
            $user->tell('<window styleclass="dpwindow_error"><h1>'
                . dp_text('Failure validating code') . '</h1><br />'
                . dp_text('Please try again.') . '</window>');
            return;
        }
        if ($next_id = dp_db_next_id('Users', 'userId')) {
            $username = $user->_GET['username'];
            $keys = $vals = array();
            $keys[] = 'userId';
            $vals[] = dp_db_quote($next_id);
            $keys[] = 'userUsername';
            $vals[] = dp_db_quote($user->_GET['username']);
            $keys[] = 'userUsernameLower';
            $vals[] = dp_db_quote(dp_strtolower($user->_GET['username']));
            $keys[] = 'userPassword';
            $vals[] = dp_db_quote($user->_GET['password']);
            $keys[] = 'userCookieId';
            $vals[] = dp_db_quote($cookie_id = make_random_id());
            $keys[] = 'userCookiePassword';
            $vals[] = dp_db_quote($cookie_pass = make_random_id());
            $keys = implode(',', $keys);
            $vals = implode(',', $vals);

            if (FALSE !==
                    dp_db_exec("INSERT INTO Users ($keys) VALUES ($vals)")) {
                $user->tell('<cookie>removeguest</cookie>');
                $user->_COOKIE[DPSERVER_COOKIE_NAME] =
                    "$cookie_id;$cookie_pass";
                $user->tell('<cookie>' . $user->_COOKIE[DPSERVER_COOKIE_NAME]
                    . '</cookie>');
                $user->addId($username);
                $user->setTitle(ucfirst($username));
                $user->isRegistered = TRUE;
                $user->isAdmin = FALSE;
                if ($user === get_current_dpuser()) {
                    $this->mrCurrentDpUserRequest->findAndInitRegisteredDpUser(
                        $cookie_id, $cookie_pass);
                }

                foreach ($this->mDpUsers as $user_nr => &$u) {
                    if ($u[0] === $user) {
                        $this->mDpUsers[$user_nr][2] = $username;
                        $this->mDpUsers[$user_nr][3] = $cookie_id;
                        $this->mDpUsers[$user_nr][4] = $cookie_pass;
                        $this->mDpUsers[$user_nr][5] = 1;
                    }
                }
                $user->tell('<changeDpElement id="username">' . $username
                    . '</changeDpElement>');
                $user->tell(array('abstract' => '<changeDpElement id="'
                    . $user->getUniqueId() . '"><b>'
                    . $user->getAppearance(1, FALSE) . '</b></changeDpElement>',
                    'graphical' => '<changeDpElement id="'
                    . $user->getUniqueId() . '"><b>'
                    . $user->getAppearance(1, FALSE, $user, 'graphical')
                    . '</b></changeDpElement>'));
                $user->tell('<changeDpElement id="loginlink"><a href="'
                    . DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
                    . 'login.php&amp;act=logout" style="padding-left: 4px">'
                    . dp_text('Logout') . '</a></changeDpElement>');
                if ($env = $user->getEnvironment()) {
                    $env->tell(array('abstract' => '<changeDpElement id="'
                        . $user->getUniqueId() . '">'
                        . $user->getAppearance(1, FALSE) . '</changeDpElement>',
                        'graphical' => '<changeDpElement id="'
                        . $user->getUniqueId() . '">'
                        . $user->getAppearance(1, FALSE, $user, 'graphical')
                        . '</changeDpElement>'), $user);
                }
                $user->tell('<window><h1>'
                    . dp_text('Thank you for registering and welcome to DutchPIPE!')
                    . '</h1><br />' . sprintf(
                    dp_text('You are now logged in as: <b>%s</b>'), $username)
                    . '</window>');
                return;
            }
        }
        $user->tell('<window>' .
            dp_text('There was a problem registering your new user. Registration failed.')
            . '</window>');
    }

    /**
     * Validates a given CAPTCHA code against the code in the database
     *
     * @param      string    $captchaId  Captcha id given by the validation form
     * @param      string    $captchaGivenCode  User input
     * @return     boolean   TRUE if the given code was valid, FALSE otherwise
     */
    function validateCaptcha2($captchaId, $captchaGivenCode)
    {
        $captchaId = addslashes($captchaId);
        $captchaGivenCode = addslashes($captchaGivenCode);

        $result = dp_db_query('SELECT captchaId FROM Captcha WHERE '
            . 'captchaId=' . dp_db_quote($captchaId, 'integer')
            . ' AND captchaFile=' . dp_db_quote($captchaGivenCode . '.gif'));

        $rval = $result && dp_db_num_rows($result);
        dp_db_free($result);
        return $rval;
    }

    /**
     * Is the given login info valid for a new user?
     *
     * In case of errors, fills array $this->mLastNewUserErrors with one or more
     * error strings.
     *
     * @param      string    $userName   given user name to check
     * @param      string    $password   given password
     * @param      string    $password2  given repeated pssword
     * @return     boolean   TRUE for valid login info, FALSE otherwise
     */
    private function validLoginInfo($userName, $password, $password2)
    {
        $this->mLastNewUserErrors = array();

        $len = dp_strlen($userName);
        if (0 === $len) {
            $this->mLastNewUserErrors[] = '<li>'
                . dp_text('No username was given') . '</li>';
        } else {
            if ($len < DPUNIVERSE_MIN_USERNAME_LEN) {
                $this->mLastNewUserErrors[] = '<li>' . sprintf(
                    dp_text('The username must be at least %d characters long'),
                    DPUNIVERSE_MIN_USERNAME_LEN) . '</li>';
            } elseif ($len > DPUNIVERSE_MAX_USERNAME_LEN) {
                $this->mLastNewUserErrors[] = '<li>' . sprintf(
                    dp_text('The username must be at most %d characters long'),
                    DPUNIVERSE_MAX_USERNAME_LEN) . '</li>';
            }

            /*if (FALSE !== ($words = file(DUTCHPIPE_FORBIDDEN_USERNAMES_FILE))
                    && count($words)) {
                foreach ($words as $word) {
                    if (FALSE !== dp_strpos($userName, $word)) {
                        $this->lastUsernameError[] = '<li>' .
                        dp_text('This username is not allowed, please try again.')
                        . '</li>';
                        break;
                    }
                }
            }*/

            $lower_user_name = dp_strtolower($userName);
            if ($lower_user_name{0} < 'a' || $lower_user_name{0} > 'z') {
                $this->mLastNewUserErrors[] = '<li>'
                     . dp_text('Illegal character in username at position 1 (usernames must start with a letter, digits or other characters are not allowed)')
                     . '</li>';
            }
            for ($i = 1; $i < $len; $i++) {
                if (($lower_user_name{$i} < 'a' || $lower_user_name{$i} > 'z')
                        && ($lower_user_name{$i} < '0'
                        || $lower_user_name{$i} > '9')) {
                    $this->mLastNewUserErrors[] = '<li>' . sprintf(
                        dp_text('Illegal character in username at position %d (you can only use a-z and 0-9)'),
                        ($i + 1)) . '</li>';
                    break;
                }
            }

            $result = dp_db_query('SELECT userId FROM Users WHERE '
                . 'userUsernameLower=' . dp_db_quote(dp_strtolower($userName)));
            if ($result && dp_db_num_rows($result)) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dp_text('That username is already in use') . '</li>';
            }
            dp_db_free($result);
        }

        if (!isset($password) || !dp_strlen($password)) {
            $this->mLastNewUserErrors[] = '<li>'
                . dp_text('No password was given') . '</li>';
        }

        if (0 === sizeof($this->mLastNewUserErrors)) {
            if (dp_strlen($password) < 6) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dp_text('Your password must be at least 6 characters long')
                    . '</li>';
            } elseif (dp_strlen($password) > 32) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dp_text('Your password must be at most 32 characters long')
                    . '</li>';
            } elseif (!dp_strlen($password2)) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dp_text('You didn\'t repeat your password') . '</li>';
            } elseif ($password !== $password2) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dp_text('The repeated password was different') . '</li>';
            }
        }

        return 0 === sizeof($this->mLastNewUserErrors);
    }

    /**
     * Gets a random avatar image number in order to give guests an avatar
     *
     * Checks the avatar image directory for images with the format:
     * public/avatar/user<number>.gif
     * for possible numbers.
     *
     * @return  int     random avatar image number
     * @see     getNrOfAvatars
     */
    function getRandAvatarNr()
    {
        $entries = $this->getNrOfAvatars();
        if (0 === $entries) {
            return 1;
        }
        return (mt_rand(51, 50 + $entries) - 50);
    }

    /**
     * Gets the available number of avatars images
     *
     * Searches the DPUNIVERSE_AVATAR_STD_PATH directory for files ending with
     * "_body.gif".
     *
     * @return  int     the number of available avatar images
     * @see     getRandAvatarNr
     */
    function getNrOfAvatars()
    {
        $entries = 0;
        $d = dir(DPUNIVERSE_AVATAR_STD_PATH);
            while (false !== ($entry = $d->read())) {
            if ($entry !== '.' && $entry !== '..' && dp_strlen($entry) > 13
                    && dp_substr($entry, -9) == '_body.gif') {
                $entries++;
            }
        }
        return $entries;
    }

    /**
     * Sets the user been told anything this request
     *
     * @access     private
     */
    function setToldSomething()
    {
        $this->mrCurrentDpUserRequest->setToldSomething();;
    }

    /**
     * Has the user been told anything this request?
     *
     * @access     private
     * @return     boolean TRUE if user was told something, FALSE otherwise
     */
    function isToldSomething()
    {
        return $this->mrCurrentDpUserRequest->isToldSomething();
    }

    /**
     * Do not tell anything to the current http request?
     *
     * @access     private
     * @return     boolean TRUE for no telling, FALSE otherwise
     */
    function isNoDirectTell()
    {
        return $this->mNoDirectTell;
    }

    /**
     * Adds a user to the listener list of the given event
     *
     * @param      string    $event      Name of the event
     * @param      string    &$who       User listening to event
     */
    function addAlertEvent($event, &$who)
    {
        if (!isset($this->mAlertEvents[$event])) {
            $this->mAlertEvents[$event] = array();
        }

        if (FALSE === array_search($who, $this->mAlertEvents[$event], TRUE)) {
            $this->mAlertEvents[$event][] =& $who;
        }
    }

    /**
     * Removes a user from the listener list of the given event
     *
     * @param      string    $event      Name of the event
     * @param      string    &$who       User listening to event
     */
    function removeAlertEvent($event, &$who)
    {
        if (isset($this->mAlertEvents[$event]) && FALSE !==
            ($key = array_search($who, $this->mAlertEvents[$event], TRUE))) {
            unset($this->mAlertEvents[$event][$key]);
            if (0 === count($this->mAlertEvents[$event])) {
                unset($this->mAlertEvents[$event]);
            }
        }
    }

    /**
     * Gets list of listening users to the given event
     *
     * @param      string    $event      Name of the event
     * @return     array     Users listening to event
     */
    function &getAlertEvent($event)
    {
        if (!isset($this->mAlertEvents[$event])) {
            $rval = FALSE;
        } else {
            /* Clean up event listeners list */
            foreach ($this->mAlertEvents[$event] as $key => &$who) {
                if (!isset($who) || is_null($who) || $who->isRemoved) {
                    unset($this->mAlertEvents[$event][$key]);
                }
            }

            $rval = $this->mAlertEvents[$event];
        }

        return $rval;
    }

    /**
     * Shows a line with memory and universe info
     */
    function showStatusMemoryGetUsage()
    {
        if (!function_exists('memory_get_usage')) {
            return;
        }

        $info = $this->getUniverseInfo();

        printf("Memory: %dKB  #Objects: %d  #Users: %d  #Environments: %d  "
            . "#Timeouts: %d\n",
            round($info['memory_usage'] / 1024),
            $info['nr_of_objects'],
            $info['nr_of_users'],
            $info['nr_of_environments'],
            $info['nr_of_timeouts']);
    }

    /**
     * Gets a list of all objects in this DutchPIPE universe
     *
     * @return     array     A list of all objects in this DutchPIPE universe
     * @see        DpUser::actionOblist()
     * @since      DutchPIPE 0.4.0
     */
    function getObjectList()
    {
        $rval = '<table border="0" cellspacing="2" cellpadding="2" '
            . 'class="dpoblist sortable"><thead><tr>'
            . '<th nowrap="nowrap" style="white-space: nowrap">'
                . '<div style="float: left">' . dp_text('Unique Id')
            . '</div></th>'
            . '<th>' . dp_text('Title') . '</th>'
            . '<th>' . dp_text('Location') . '</th>'
            . '<th>' . dp_text('Environment') . '</th>'
            . '<th class="align_c">' . dp_text('User') . '</th>'
            . '<th class="align_c">' . dp_text('Adm') . '</th>'
            . '<th class="align_c">' . dp_text('NPC') . '</th>'
            . '<th class="align_c">' . dp_text('Page') . '</th>'
            . '<th>' . dp_text('Created') . '</th>'
            . '<th>' . dp_text('Reset') . '</th>'
            . '</tr></thead><tbody>';
        $checked = '<img src="' . DPUNIVERSE_IMAGE_URL . 'checked.gif'
            . '" width="7" height="7" border="0" alt="*" title="*" />';

        $ob_nr = 1;
        $ob_sz = count($this->mDpObjects);
        foreach ($this->mDpObjects as &$ob) {
            $rval .= '<tr>'
                . '<td sorttable_customkey="' . $ob_nr . '">'
                    . $ob->uniqueId
                . '</td>'
                . '<td><div>' . ucfirst($ob->title) . '</div></td>'
                . '<td><div>' . $ob->location . '</div></td>'
                . '<td><div>'
                    . (!($env = $ob->getEnvironment()) ? '&nbsp;'
                    : '<a href="' . (!$env->isStandalone ? DPSERVER_CLIENT_URL
                    . '?location=' : '') . $env->location . '">'
                    . ucfirst($env->title)
                    . '</a>')
                . '</div></td>'
                . '<td class="align_c"' . (!$ob->isUser
                    ? ' sorttable_customkey="' . $ob_sz . '">&nbsp;'
                    : '">' . $checked)
                . '</td>'
                . '<td class="align_c"' . (!$ob->isAdmin
                    ? ' sorttable_customkey="' . $ob_sz . '">&nbsp;'
                    : '">' . $checked)
                . '</td>'
                . '<td class="align_c"' . (!$ob->isNpc
                    ? ' sorttable_customkey="' . $ob_sz . '">&nbsp;'
                    : '">' . $checked)
                . '</td>'
                . '<td class="align_c"' . (!$ob->isPage
                    ? ' sorttable_customkey="' . $ob_sz . '">&nbsp;'
                    : '">' . $checked)
                . '</td>'
                . '<td><div>'
                    . strftime('%d %b %H:%M', $ob->creationTime)
                . '</div></td>'
                . '<td><div>'
                    . strftime('%H:%M', $ob->resetTime)
                . '</div></td>'
                . '</tr>';
            $ob_nr++;
        }

        return $rval . '</tbody></table>';
    }
}
?>