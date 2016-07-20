<?php
/**
 * Provides 'DpUniverse' class to handle a specific 'universe'
 *
 * Defines DpObjects, users, pages, etc. (our 'rules of nature')
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
 * @version    Subversion: $Id: dpuniverse.php 15 2006-05-18 21:50:46Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver.php, dpfunctions.php
 */

// Shows all possible errors:
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

define('_DPUSER_OBJECT', 0);           // Reference to /std/DpUser.php object
define('_DPUSER_MESSAGES', 1);         // Array of strings with messages
define('_DPUSER_NAME', 2);             // Name of user behind http request
define('_DPUSER_COOKIEID', 3);         // Cookie Id used for authorization
define('_DPUSER_COOKIEPASS', 4);       // Cookie Pass used for authorization
define('_DPUSER_ISREGISTERED', 5);     // Is it a registered user?
define('_DPUSER_TIME_LASTREQUEST', 6); // Last http request UNIX time
define('_DPUSER_CURRENT_SCRIPTID', 7); // Script id of current AJAX request
define('_DPUSER_LAST_SCRIPTID', 8);    // Script id of last AJAX request

/**
 * A DutchPIPE universe, handling objects, users, pages, etc. (rules of nature)
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpUniverse
{
    /**
     * All objects on the site
     */
    public $mDpObjects = array();

    /**
     * Reset queue, first element will reset first
     */
    public $mDpObjectResets = array();

    /**
     * The environment of each object on the site in env => ob pairs.
     */
    public $mEnvironments = array();

    /**
     * All user objects on the site + data
     *
     * Each element in the array represents a user. A user is stored in another
     * array, with the elements as defined by the _DPUNIVER_USER_ definitions.
     */
    public $mDpUsers = array();

    /**
     * All pending timeouts on the site after use of setTimeout()
     */
    public $mTimeouts = array();

    /**
     * Increasing guest counter used to form unique names for guests, for
     * example "Guest#8'
     */
    public $mGuestCnt;

    /**
     * DpObject counter increased by newDpObject, used to generate unique object
     * ids
     */
    public $mUniqueDpObjectCnt = 1;

    /**
     * The server object that called us
     */
    public $mrDpServer;

    /**
     * Info on the current HTTP user request
     */
    public $mrCurrentDpUserRequest;

    /**
     * Do not tell anything to the current http request?
     */
    public $mNoDirectTell = FALSE;

    /**
     * Constructs this universe based on a universe ini file
     */
    function __construct($iniFile = 'dpuniverse-ini.php')
    {
        /* Gets the universe settings */
        require_once($iniFile);

        $this->mGuestCnt = mt_rand(25, 75);

        /* These functions will be available for all objects */
        require_once(DPUNIVERSE_LIB_PATH . 'dpfunctions.php');

        mysql_pconnect(DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER,
            DPUNIVERSE_MYSQL_PASSWORD)
            || die('Could not connect: ' . mysql_error() . "\n");

        mysql_select_db(DPUNIVERSE_MYSQL_DB)
            || die('Failed to select database: ' . DPUNIVERSE_MYSQL_DB . "\n");
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
     */
    function handleCurrentDpUserRequest(&$rDpServer = NULL, &$rServerVars = NULL,
            &$rSessionVars = NULL, &$rCookieVars = NULL, &$rGetVars = NULL,
            &$rPostVars = NULL, &$rFilesVars = NULL)
    {
        if (!is_null($rDpServer)) {
            $this->mrDpServer = $rDpServer;
            $GLOBALS['grCurrentDpUniverse'] = &$this;
        }

        // Because we don't use a 'ticks' system or something similar yet,
        // user requests are used to handle some generic cyclic calls:
        $this->handleLinkdead();
        $this->handleReset();

        $this->mrCurrentDpUserRequest = new CurrentDpUserRequest($this, $rServerVars,
            $rSessionVars, $rCookieVars, $rGetVars, $rPostVars, $rFilesVars);
        if (FALSE === $this->mrCurrentDpUserRequest->checkUser()) {
            if (!isset($rGetVars['ajax'])) {
                $this->tellCurrentDpUserRequest('<event><div id="dppage"><![CDATA[Your browser did not report a '
                    . 'User Agent string to the server. This is required.<br />'
                    . ']]></div></event>');
            }
            return;
        }
        if (FALSE === $this->mrCurrentDpUserRequest->mCookieId) {
            $this->tellCurrentDpUserRequest('2');
            return;
        }

        // Because we don't use a 'ticks' system or something similar yet,
        // user requests are used to handle some generic cyclic calls:
        $this->handleRunkit();

        $this->mrCurrentDpUserRequest->handleLocation();

        $this->mDpUsers[$this->mrCurrentDpUserRequest->mUserArrKey]
            [_DPUSER_TIME_LASTREQUEST] = time();

        // scriptids are used to detect if a user has multiple browser windows
        // open. Each initiated dpclient.js sets such a random id:
        $old_scriptid = $this->mDpUsers[$this->mrCurrentDpUserRequest->mUserArrKey]
            [_DPUSER_CURRENT_SCRIPTID];
        $new_scriptid = is_null($rGetVars) || !isset($rGetVars['ajax'])
            || !isset($rGetVars['scriptid']) || 0 === (int)$rGetVars['scriptid']
            ? FALSE : $rGetVars['scriptid'];

        if (FALSE !== $old_scriptid && FALSE !== $new_scriptid
                && $old_scriptid !== $new_scriptid) {
            $this->mDpUsers[$this->mrCurrentDpUserRequest->mUserArrKey]
                [_DPUSER_LAST_SCRIPTID] = FALSE;
            $this->mDpUsers[$this->mrCurrentDpUserRequest->mUserArrKey]
                [_DPUSER_CURRENT_SCRIPTID] = FALSE;
            $this->mDpUsers[$this->mrCurrentDpUserRequest->mUserArrKey]
                [_DPUSER_OBJECT]->tell('close_window');
        } else {
            $this->mDpUsers[$this->mrCurrentDpUserRequest->mUserArrKey]
                [_DPUSER_LAST_SCRIPTID] = $old_scriptid;
            $this->mDpUsers[$this->mrCurrentDpUserRequest->mUserArrKey]
                [_DPUSER_CURRENT_SCRIPTID] = $new_scriptid;
        }

        // Because we don't use a 'ticks' system or something similar yet,
        // user requests are used to handle some generic cyclic calls:
        $this->handleTimeouts();

        $this->mrCurrentDpUserRequest->handleUser();
    }

    /**
     * Checks if people left the site, throws them out of the universe
     */
    function handleLinkdead()
    {
        $cur_time = time();

        // Time the user's browser should have done a page or AJAX request:
        $linkdeath_time = $cur_time - DPUNIVERSE_LINKDEATH_KICKTIME;

        // Currently, this function is triggered by a user http request. If
        // there's one user and he leaves, then if the next user enters, say
        // a minute later (this time is defined here), he should not see
        // 'X leaves the site.':
        $showmsg_time = $linkdeath_time - DPUNIVERSE_LINKDEATH_SHOWMSGTIME;

        // Bots may be visible a bit longer, since they don't have Javacript
        // and sometimes do a lot of requests. With a short 'kick' time, users
        // would get a lot of 'Bot enters the site' and 'Bot leaves the site'
        // messages:
        $botkick_time = $cur_time - DPUNIVERSE_BOT_KICKTIME;

        $showbot_time = $botkick_time - DPUNIVERSE_LINKDEATH_SHOWBOTTIME;

        foreach ($this->mDpUsers as $i => &$u) {
            // Throw out people who lost connection or browsed elsewhere. Need
            // to think this one out (move them to a void, etc.), something
            // simple for now:
            $lastrequest_time = $u[_DPUSER_TIME_LASTREQUEST];
            $ajax_capable = $u[_DPUSER_OBJECT]->getProperty(
                'is_ajax_capable');

            // The "linkdeath" check:
            if (($ajax_capable && $lastrequest_time < $linkdeath_time)
                    || (!$ajax_capable && $lastrequest_time < $botkick_time)) {

                // This method is called before an instance of CurrentDpUserRequest
                // is created and the current http request is handled. However,
                // the tell() functions operate on the current request.
                // Therefore, any sound made should be stored for the next
                // cycle, otherwise the people get wrong messages.
                // tell() in the User class checks for this variable:
                $this->mNoDirectTell = TRUE;

                if (FALSE !== ($env =
                        $u[_DPUSER_OBJECT]->getEnvironment())) {
                    if (($ajax_capable && $lastrequest_time > $showmsg_time)
                            || (!$ajax_capable
                            && $lastrequest_time > $showbot_time)) {
                        // Drop stuff, tell people on the page the user left:
                        $u[_DPUSER_OBJECT]->actionDrop('drop', 'all');
                        $env->tell(ucfirst($u[_DPUSER_OBJECT]->getTitle(
                            DPUNIVERSE_TITLE_TYPE_DEFINITE))
                            . ' left the site.<br />',
                            $u[_DPUSER_OBJECT]);
                    } else {
                        // Drop all silently:
                        $u[_DPUSER_OBJECT]->actionDrop('drop', 'all',
                            TRUE);
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
     */
    function handleReset()
    {
        // Perform a limited number of resets per cycle:
        $max_resets = DPUNIVERSE_MAX_RESETS;

        // Pick up where we were in the reset array:
        while (($ob = current($this->mDpObjectResets)) && $max_resets--) {
            // Checks if the current object ready to reset:
            if ($ob->getProperty('reset_time') > time()) {
                // No need to go on, check next http request cycle:
                return;
            }

            // Resets the object, sets next reset time:
            $ob->__reset();
            $ob->addProperty('reset_time', time() + DPUNIVERSE_RESET_CYCLE);

            if (FALSE === next($this->mDpObjectResets)) {
                reset($this->mDpObjectResets);
            }

            // Check the next object:
            continue;
        }
    }

    /**
     * Optionally uses experimental PHP runkit module to catch errors in code
     */
    function handleRunkit()
    {
        if (FALSE !== DPUNIVERSE_RUNKIT) {
            if (FALSE === @runkit_lint_file(DPUNIVERSE_BASE_PATH
                    . $this->__GET['location'])) {
                if ($this->mrUser->getEnvironment()) {
                    $this->mrEnvironment = $this->mrUser->getEnvironment();
                }
                $this->__GET['location'] =
                    FALSE === is_null($this->mrEnvironment)
                    ? $this->mrEnvironment->getProperty('location')
                    : DPUNIVERSE_PAGE_PATH . 'index.php';
                $this->mrUser->tell('<location>' . $__GET['location']
                    . '</location>');
                $this->mMoveError = 'You notice a disruptance.<br />';
            }
        }
    }

    /**
     * Handles delayed function calls requested by objects in the universe
     */
    function handleTimeouts()
    {
        while (sizeof($this->mTimeouts)) {
            foreach ($this->mTimeouts as $i => $to) {
                if (FALSE !==
                        $this->mTimeouts[$i][0]->getProperty('is_removed')) {
                    unset($this->mTimeouts[$i]);
                    break;
                }
                $cur_time = time();
                if ($this->mTimeouts[$i][2] <= $cur_time) {
                    $object = $this->mTimeouts[$i][0];
                    $method = $this->mTimeouts[$i][1];
                    $object->$method();
                    unset($this->mTimeouts[$i]);
                    break;
                }
                break 2;
            }
        }
    }

    /**
     * Stores user agent information in the database
     *
     * Experimental. Used to catch search bots.
     */
    function saveUserAgent(&$user)
    {
        $agent = !isset($user->__SERVER['HTTP_USER_AGENT'])
            || 0 === strlen($user->__SERVER['HTTP_USER_AGENT'])
            ? '[Undefined]'
            : addslashes($user->__SERVER['HTTP_USER_AGENT']);

        if ($user->getProperty('is_ajax_capable')) {
            $result = mysql_query($query = "SELECT userAgentId from UserAgents "
                . "WHERE userAgentString='$agent'");
            echo "$query\n";
            if (FALSE === $result
                    || !($num_rows = mysql_num_rows($result))) {
                mysql_query($query = "INSERT INTO UserAgents (userAgentString) "
                    . "VALUES ('$agent')");
                echo "$query\n";
            }
        } else {
            $remote_address = !isset($user->__SERVER['REMOTE_ADDR'])
                || 0 === strlen($user->__SERVER['REMOTE_ADDR'])
                ? '[Undefined]'
                : addslashes($user->__SERVER['REMOTE_ADDR']);
            $result = mysql_query($query = "SELECT userAgentId from UserAgents "
                . "WHERE userAgentString='$agent' and userAgentRemoteAddress="
                . "'$remote_address'");
            echo "$query\n";
            if (FALSE === $result
                    || !($num_rows = mysql_num_rows($result))) {
                mysql_query($query = "INSERT INTO UserAgents (userAgentString, "
                    . "userAgentRemoteAddress) VALUES ('$agent', "
                    . "'$remote_address')");
                echo "$query\n";
            }
        }
    }

    /**
     * Stores a message for a user while we wait for another http request
     *
     * Called from tell in DpUser.php
     *
     * :WARNING: This method should normally only be called from DpUser.php.
     */
    function storeTell(&$user, $data, &$binded_environment = NULL)
    {
        foreach ($this->mDpUsers as $i => &$u) {
            if ($u[_DPUSER_OBJECT] === $user) {
                $this->mDpUsers[$i][1][] = array($data, $binded_environment);
            }
        }
    }

    /**
     * Tells something back to the currently connected user client
     *
     * :WARNING: This method should normally only be called from DpUser.php.
     */
    function tellCurrentDpUserRequest($talkback)
    {
        $this->mrDpServer->tellCurrentDpUserRequest($talkback);
        $this->mrCurrentDpUserRequest->mToldSomething = TRUE;
    }

    /**
     * Gets db row key of a random CAPTCHA code for the user registration page
     *
     * Used to seperate software robots from real people during registration.
     * Codes aren't generated on the fly and as such not really random because
     * the CPU time penalty is too high. A directory with pre-generated codes
     * exist and a database table with info. A cronjob makes one every hour and
     * replaces the oldest one in the database. Then we obtain a random entry
     * here. This should do it for now, but your milleague might vary and the
     * situation might change as spambots get smarter.
     */
    function getRandCaptcha()
    {
        $result = mysql_query("SELECT captchaId FROM Captcha");

        if (FALSE === $result || !($num_rows = mysql_num_rows($result))) {
            return FALSE;
        }

        return mysql_result($result, mt_rand(0, $num_rows - 1), 0);
    }

    /**
     * Validates a given CAPTCHA code against the code in the database
     *
     * @param      string    $captchaId  Captcha id given by the validation form
     * @param      string    $captchaGivenCode  User input
     * @return     boolean   TRUE if the given code was valid, FALSE otherwise
     */
    function validateCaptcha($captchaId, $captchaGivenCode)
    {
        $captchaId = addslashes($captchaId);
        $captchaGivenCode = addslashes($captchaGivenCode);

        $result = mysql_query("SELECT captchaId FROM Captcha WHERE
            captchaId='$captchaId' AND captchaFile='$captchaGivenCode.gif'");

        return $result && mysql_num_rows($result);
    }

    /**
     * Creates a new object in the universe
     *
     * You MUST call this function to create new objects in a DutchPIPE
     * universe, don't use the 'new' construct directly.
     *
     * @param      string    $pathname   path to code from universe base path
     * @param      boolean   $proxy      expirimental, ignore
     * @return     object    The newly created object
     */
    function &newDpObject($pathname, $proxy = FALSE)
    {
        $unique_id = $this->mUniqueDpObjectCnt;
        $this->mUniqueDpObjectCnt++;

        if (FALSE !== $proxy) {
            require_once(DPUNIVERSE_BASE_PATH . DPUNIVERSE_STD_PATH
                . 'DpPage.php');
            $object = new DpPage($unique_id);
            $object->setTitle('Test');
            $object->setBody("http://www.dutchpipe.org$pathname", 'url');
            $object->setNavigationTrail(array(DPUNIVERSE_NAVLOGO, '/'),
                $object->getTitle());
            $object->addProperty('is_layered', TRUE);
        } elseif (substr($pathname, 0, 1) != '/') {
            require_once(DPUNIVERSE_BASE_PATH . DPUNIVERSE_STD_PATH
                . 'DpPage.php');
            $object = new DpPage($unique_id);
            $object->setTitle($pathname);
            $this->__GET['location'] = $pathname;
        } else {
            require_once(DPUNIVERSE_BASE_PATH . $pathname);
            $classname =  explode("/", $pathname);
            $classname = ucfirst(!strlen($classname[sizeof($classname) - 1]) ?
                'index' : substr($classname[sizeof($classname) - 1], 0, -4));
            $object = new $classname($unique_id);
        }
        $object->addProperty('location', $pathname);
        $object->addProperty('reset_time', time() + DPUNIVERSE_RESET_CYCLE);

        $this->mDpObjects[] =& $object;
        $this->mDpObjectResets[] =& $object;
        echo "made new object $pathname\n";
        return $object;
    }

    /**
     * Moves the object to another environment
     *
     * This method only handles the internal storage of environments.
     * Most functionality can be found in  moveDpObject in DpObject.php.
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     */
    function moveDpObject(&$item, &$dest)
    {
        foreach ($this->mEnvironments as $i => &$pair) {
            if ($pair[0] === $item) {
                $this->mEnvironments[$i][1] =& $dest;
                return;
            }
        }

        $this->mEnvironments[] = array(&$item, &$dest);
    }

    /**
     * Removes this object
     *
     * The object is destroyed and no longer part of the universe.
     * This method only handles the internal storage of objects.
     * Most functionality can be found in removeDpObject in DpObject.php.
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     */
    function removeDpObject(&$target)
    {
        echo "removeDpObject() called in universe.\n";
        foreach ($this->mDpUsers as $i => &$u) {
            if ($u[_DPUSER_OBJECT] === $target) {
                $del_user = $i;
                break;
            }
        }
        foreach ($this->mEnvironments as $i => &$env) {
            if ($env[0] === $target) {
                $del_env = $i;
                break;
            }
        }
        foreach ($this->mDpObjects as $i => &$ob) {
            if ($ob === $target) {
                $del_obj = $i;
                break;
            }
        }
        foreach ($this->mDpObjectResets as $i => &$ob) {
            if ($ob === $target) {
                $del_reset = $i;
                break;
            }
        }

        if (isset($del_user)) {
            unset($this->mDpUsers[$del_user]);
        }
        if (isset($del_env)) {
            unset($this->mEnvironments[$del_env]);
        }
        if (isset($del_obj)) {
            unset($this->mDpObjects[$del_obj]);
        }
        if (isset($del_reset)) {
            unset($this->mDpObjectResets[$del_reset]);
        }
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
     * @param      string    $pathname   a path within dpuniverse/
     * @param      boolean   $proxy      experimental, ignore
     * @return     object    Reference to instance of $pathname
     */
    function &getDpObject($pathname, $proxy = FALSE)
    {
        foreach ($this->mDpObjects as $i => &$ob) {
            if ($pathname === $ob->getProperty('location')) {
                return $ob;
            }
        }

        return $this->newDpObject($pathname, $proxy);
    }

    /**
     * Gets the object reference to the environment of this object
     *
     * Don't call this method here, call it in objects in the universe instead,
     * for instance $user->getEnvironment().
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
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
     * Search an environment for an object, as specified in 'what'.
     * If 'what' is a string the objects searched are checked for
     * returning 1 on the call id(what).
     *
     * If 'where' is not specified, the inventory of the current object,
     * and its environment (in that order), are searched for 'what'.
     *
     * If 'where' is specified, then only the inventory of 'where' is searched.'
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @param       string|object   $what    description
     * @param       object          $where  description
     * @return      object|boolean  Returns the found object if 'what' was found.
     *                              Returns FALSE if 'what' was not found.
     * @see         get_environment, get_inventory
     */
    function &isPresent($what, &$where)
    {
        $inv = $this->getInventory($where);
        $rval = FALSE;
        if (sizeof($inv)) {
            if (is_string($what) && strlen($what = trim($what))) {
                $what = strtolower($what);
                $nr = 1;
                if (FALSE !== ($pos = strrpos($what, ' '))) {
                    $nr = substr($what, $pos + 1);
                    if (FALSE === is_whole_number($nr)) {
                        $nr = 1;
                    }
                    else {
                        $nr = (int)$nr;
                        $what = trim(substr($what, 0, $pos));
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
     * Finds the user with the given user name or id.
     *
     * @param       string   $name   user name or id of player
     * @return      object|boolean   The found player or FALSE if not found
     */
    function &findUser($name)
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

        // :TODO: Since this will be used a lot, keep a seperate copy instead
        // of constructing this arary each time:
        foreach ($this->mDpUsers as &$u) {
            $users[] =& $u[_DPUSER_OBJECT];
        }

        return $users;
    }

    /**
     * Calls the given method after the given number of seconds
     *
     * Use this to perform delayed method calls in the given object. Note that
     * functions such as get_current_dpuser can be totally different when the
     * method is called. Also note that the actual delay is not exact science.
     *
     * :WARNING: This method should normally only be called from DpObject.php.
     *
     * @param      object    &$ob        reference to object to call method in
     * @param      string    $method     name of method to call
     * @param      int d     $secs       delay in seconds
     */
    function setTimeout(&$ob, $method, $secs)
    {
        if (is_object($ob) && method_exists($ob, $method) && $secs > 0) {
            $this->mTimeouts[] = array(&$ob, $method, time() + $secs);
        }
    }
}

class CurrentDpUserRequest
{
    /**
     * References to the universe object:
     */
    public $mrDpUniverse;
    public $__SERVER;
    public $__SESSION;
    public $__COOKIE;
    public $__GET;
    public $__POST;
    public $__FILES;
    public $mrEnvironment;

    /**
     * Reference to the user object in the universe
     */
    public $mrUser;

    /**
     * Key to user object above in array with all users for quick lookups
     */
    public $mUserArrKey;

    /**
     * Is this user request coming from a registered user?
     */
    public $mIsRegistered = FALSE;

    /**
     * The user name behind the current user client request
     */
    public $mUsername;

    /**
     * The 'cookie id' of the user client behind the current request
     *
     * We don't store the users real username and password in his cookie, but
     * something we generated and stored in the user's database entry. If the
     * cookie is stolen, the username/password isn't.
     */
    public $mCookieId;

    /**
     * The 'cookie password' of the user client behind the current request
     *
     * We don't store the users real username and password in his cookie, but
     * something we generated and stored in the user's database entry. If the
     * cookie is stolen, the username/password isn't.
     */
    public $mCookiePass;

    /**
     * Something old that should be ripped out
     *
     * Used in the beginning to check if we needn't to pass updates on:
     */
    public $mToldSomething;

    /**
     * True if the user tried to move but failed
     *
     * Used if the experimental PHP runkit module is used. If for instance a
     * page object contins a syntax error and a user tries to go there, this
     * flag will be true.
     */
    public $mMoveError;

    /**
     * :KLUDGE: Used as a kludge to handle user movements within this request
     */
    public $mHasMoved;

    /**
     * Is this user a known search bot/spider/crawler?
     */
    public $mIsKnownBot = FALSE;

    /**
     * Sets up the object handling the current user request
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

    function checkUser()
    {
        /* Do we have a session cookie (guest) or fixed cookie (registered)? */
        if (isset($this->__COOKIE)
                && isset($this->__COOKIE['dutchpipe'])
                && strlen($this->__COOKIE['dutchpipe'])
                && strlen($cookie_data =
                    trim($this->__COOKIE['dutchpipe'], ';'))
                && sizeof($cookie_data = explode(';', $cookie_data))
                && 2 === sizeof($cookie_data)) {
            $this->mCookieId = $cookie_data[0];
            $this->mCookiePass = $cookie_data[1];
            /* Is the user already on this site? */
            foreach ($this->mrDpUniverse->mDpUsers as $user_arr_key => &$u) {
                if ($u[_DPUSER_COOKIEID] === $this->mCookieId
                        && $u[_DPUSER_COOKIEPASS] === $this->mCookiePass) {
                    $this->mrUser = &$u[_DPUSER_OBJECT];
                    $this->mUsername = $u[_DPUSER_NAME];
                    if ($u[_DPUSER_ISREGISTERED] == '1') {
                        $this->mrUser->addProperty('is_registered');
                        $this->mIsRegistered = TRUE;
                    } else {
                        $this->mrUser->removeProperty('is_registered');
                    }
                    $this->mUserArrKey = $user_arr_key;
                }
            }
            /* Skip Ajax database check for now, but what are the security
               implications? */
            if (!isset($this->mrUser)) {
                $result = mysql_query("SELECT userUsername FROM Users WHERE userCookieId='{$this->mCookieId}' and userCookiePassword='{$this->mCookiePass}'");
                if (empty($result) || !($row = mysql_fetch_array($result))) {
                    $this->mrUser = NULL;
                    $this->mUsername = NULL;
                    $this->mIsRegistered = FALSE;
                    $this->mUserArrKey = NULL;
                } else {
                    $this->mUsername = $row[0];
                    $this->mIsRegistered = TRUE;
                }
            }
        } else {
            if (isset($this->__GET) && isset($this->__GET['ajax'])) {
                $this->mCookieId = FALSE;
                $this->mUsername = 'Cookieless';
                echo "NO COOKIE\n\n";
                return TRUE;
            }
        }
        if (!isset($this->mUsername)) {
            if (FALSE === $this->setupGuest()) {
                return FALSE;
            }
        }
        if (!isset($this->mrUser) || empty($this->mrUser)) {
            $this->createUser();
        }
        if (FALSE === $this->handleCleanLocation()) {
            $tmp = $this->mrUser->getEnvironment();
            if (isset($this->__GET['method']) && FALSE !== $tmp) {
                $this->__GET['location'] = $tmp->getProperty('location');
            } elseif (isset($this->mrUser->__GET['proxy'])
                    || (FALSE !== $tmp && (isset($this->mrUser->__GET['ajax'])
                    || isset($this->__GET['method']))
                    && FALSE !== $tmp->getProperty('is_layered'))) {
                $this->__GET['location'] = $tmp->getProperty('location');
            } else {
                $this->__GET['location'] = DPUNIVERSE_PAGE_PATH . 'index.php';
            }
        }
        $this->mrUser->setVars($this->__SERVER, $this->__SESSION, $this->__COOKIE, $this->__GET, $this->__POST, $this->__FILES);
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
     */
    function setupGuest()
    {
        /* Gets the browser name of the user, a.k.a. the user agent */
        $agent = !isset($this->__SERVER['HTTP_USER_AGENT'])
                || 0 === strlen($this->__SERVER['HTTP_USER_AGENT'])
            ? FALSE : $this->__SERVER['HTTP_USER_AGENT'];

        /* What IP address is the user coming from? */
        $remote_address = !isset($this->__SERVER['REMOTE_ADDR'])
                || 0 === strlen($this->__SERVER['REMOTE_ADDR'])
            ? FALSE : $this->__SERVER['REMOTE_ADDR'];

        /*
         * Give special guest names to some well known search bots.
         * Otherwise the name will be Guest#<number>.
         */

        if (FALSE === $agent) {
            echo "No agent\n";
            return FALSE;
        }
        $result = mysql_query("SELECT userAgentTitle FROM UserAgentTitles "
            . "WHERE userAgentString='$agent'");
        if (FALSE === $result || !($row = mysql_fetch_array($result))) {
            $is_known_bot = FALSE;
            $username = 'Guest#' . $this->mrDpUniverse->getGuestCnt();
        } else {
            $is_known_bot = TRUE;
            $username = $row[0];
        }

        $this->mUsername = $username;
        $this->mCookieId = make_random_id();
        $this->mCookiePass = make_random_id();
        $this->mIsKnownBot = $is_known_bot;
        echo "Set-Login: {$this->mCookieId};{$this->mCookiePass}\n";
        $this->mrDpUniverse->tellCurrentDpUserRequest(
            "Set-Login: {$this->mCookieId};{$this->mCookiePass}");
        return TRUE;
    }

    /**
     * Checks if the given location exists
     */
    function handleCleanLocation()
    {
        if (!isset($this->__GET['location'])
                || !strlen($this->__GET['location'])) {
            return isset($this->__GET['getdivs']);
        }

        if (FALSE !== ($pos = strpos($this->__GET['location'], '?'))) {
            $this->__GET['location'] =
                substr($this->__GET['location'], 0, $pos);
        }

        /* Experimental */
        if (isset($this->__GET['proxy'])
                || 0 === strpos($this->__GET['location'], '/mailman2/')) {
            return TRUE;
        }

        if (7 < strlen($this->__GET['location'])
                && 'http://' === substr($this->__GET['location'], 0, 7)) {
            return TRUE;
        }

        return (file_exists(DPUNIVERSE_BASE_PATH . $this->__GET['location'])
                && is_file(DPUNIVERSE_BASE_PATH . $this->__GET['location']))
            || (file_exists(DPUNIVERSE_WWW_PATH . $this->__GET['location'])
                && is_file(DPUNIVERSE_WWW_PATH . $this->__GET['location']));
    }

    /**
     * Creates a new user object
     */
    function createUser()
    {
        echo "createUser\n";
        $this->mrUser = $this->mrDpUniverse->newDpObject(DPUNIVERSE_STD_PATH
            . 'DpUser.php');
        $this->mrUser->addId($this->mUsername);
        $this->mrUser->setTitle(ucfirst($this->mUsername));

        if (FALSE !== $this->mIsKnownBot) {
            $this->mrUser->setTitleImg(DPUNIVERSE_IMAGE_URL . 'bot.gif');
            $this->mrUser->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
                . 'bot.gif" border="0" alt="" align="left" '
                . 'style="margin-right: 15px" />This is a search engine '
                . 'indexing this site.<br />');
        }

        if (FALSE !== $this->mIsRegistered) {
            $this->mrUser->addProperty('is_registered');
        }
        if ($this->mUsername == 'Lennert') {
            $this->mrUser->addProperty('is_admin');
        }
        if (FALSE === $this->mCookieId) {
            $this->mrUser->addProperty('no_cookies');
        }
        if (FALSE !== $this->mIsKnownBot) {
            $this->mrUser->addProperty('is_known_bot');
        }
        $this->mrDpUniverse->mDpUsers[] = array(
            $this->mrUser,
            array(),
            $this->mUsername,
            $this->mCookieId,
            $this->mCookiePass,
            $this->mIsRegistered,
            0,
            FALSE,
            FALSE);

        end($this->mrDpUniverse->mDpUsers);
        $this->mUserArrKey = key($this->mrDpUniverse->mDpUsers);
        echo "User created\n";
    }

    function handleLocation()
    {
        //echo "handleLocation()\n";
        if (is_null($this->mrEnvironment)) {
            echo "getting location {$this->__GET['location']}\n";
            $this->mrEnvironment = $this->mrDpUniverse->getDpObject($this->__GET['location'], isset($this->__GET['proxy']) || 0 === strpos($this->__GET['location'], '/mailman2'));
        }

        if (FALSE === ($env = $this->mrUser->getEnvironment()) || $env !== $this->mrEnvironment) {
            //echo "\n\n" . $location ."\n\n";
            if (!$env) {
                $from_where = 'enters the site';
            } else {
                $env->tell(ucfirst($this->mrUser->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)) . ' leaves to ' . $this->mrEnvironment->getTitle() . '.<br />', $this->mrUser);
                $from_where = 'arrives from ' . $env->getTitle();
            }
            $this->mrUser->moveDpObject($this->mrEnvironment);
            $this->mrEnvironment->tell(ucfirst($this->mrUser->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)) . " $from_where.<br />", $this->mrUser);
        }
        elseif (!isset($this->__GET['ajax']) && !isset($this->__GET['method'])) {
            echo "handleLocation getAppearance\n";
            if (FALSE !== ($body = $this->mrEnvironment->getAppearance(0, TRUE, NULL, $this->mrUser->getProperty('display_mode')))) {
                $this->mrUser->tell('<div id="dppage">' . $body . '</div>');
            }
        }
        elseif (isset($this->__GET['method'])) {
            if (!isset($this->__GET['call_object']) || !strlen($call_object = $this->__GET['call_object'])) {
                $this->mrEnvironment->{$this->__GET['method']}();
            }
            else {
                if (FALSE !== ($call_object =
                        $this->mrDpUniverse->findDpObject($call_object))) {
                    $call_object->{$this->__GET['method']}();
                }
            }
        }
        if (isset($this->__GET['getdivs'])) {
            $getdivs = explode('#', trim($this->__GET['getdivs'], '#'));
            foreach ($getdivs as $getdiv) {
                echo "getdiv: $getdiv\n";
                if ($getdiv == 'dpinventory') {
                    $this->mrUser->tell($this->mrEnvironment->getAppearanceInventory(0, TRUE, $this->mrUser, $this->mrUser->getProperty('display_mode')));
                } elseif ($getdiv == 'dpmessagearea') {
                    $this->mrUser->tell('<div id="dpmessagearea">
            <div id="dpmessagearea_inner">
                <div id="messages"><br clear="all" /></div><br clear="all" />
                <form id="actionform" method="get" onSubmit="return action_dutchpipe()"><input id="action" type="text" name="action" value="" size="40" maxlength="255" style="float: left; margin-top: 0px" /></form>
            </div><br clear="all" />&#160;
        </div>');
                }
            }
        }
        $this->mToldSomething = FALSE;
    }

    function handleUser()
    {
        if (isset($this->__GET['ajax'])) {
            $this->mrUser->addProperty('is_ajax_capable');
        }
        // Skip once if the user has moved and hence the request died.
        // Need to think if this must account for the three other calls below too:
        if (!isset($this->mHasMoved)) {
            $this->handleMessages();
        }
        $this->handleAction();
        $this->handleMoverror();
        $this->handleNothingTold();
    }

    /**
     * Checks for and handles actions by the current user
     */
    function handleAction()
    {
        if (isset($this->__GET) && is_array($this->__GET)
                && isset($this->__GET['action'])) {
            $this->mrUser->performAction(htmlspecialchars(
                (string)$this->__GET['action']));
        }
    }

    /**
     * Checks for stored messages for the current user
     */
    function handleMessages()
    {
        // Loop through all users to find the current user:
        foreach ($this->mrDpUniverse->mDpUsers as $i => &$u) {
            // Check if this is the current user:
            if ($u[_DPUSER_OBJECT] === $this->mrUser) {
                // Tell user all stored messages:
                foreach ($u[_DPUSER_MESSAGES] as &$message) {
                    if (is_null($message[1])
                            || (FALSE !== ($env = $u[_DPUSER_OBJECT]->getEnvironment())
                            && $env === $message[1])) {
                        $this->mrUser->tell($message[0]);
                        $this->mToldSomething = TRUE;
                    } else {
                    }
                }
                // Delete these stored messages:
                $this->mrDpUniverse->mDpUsers[$i][_DPUSER_MESSAGES] = array();
            }
        }
    }

    function handleMoverror()
    {
        if (isset($this->mMoveError)) {
            $tmp = $this->mUsername;
            $this->mUsername = 'ddfjjsdfdfj';
            $this->mrUser->tell($this->mMoveError);
            unset($this->mMoveError);
            $this->mUsername = $tmp;
        }
    }

    function handleNothingTold()
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
}
?>
