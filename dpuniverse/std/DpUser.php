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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpUser.php 2 2006-05-16 00:20:42Z ls $
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
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpUser extends DpLiving
{
    /**
     * @var         array     Variables set by the web server or otherwise
     *                        directly related to the execution environment of
     *                        the current dpclient script
     */
    public $__SERVER;

    /**
     * @var         array     Variables which are currently registered to a
     *                        script's session
     */
    public $__SESSION;

    /**
     * @var         array     Variables provided via HTTP cookies
     */
    public $__COOKIE;

    /**
     * @var         array     Variables provided via URL query string
     */
    public $__GET;

    /**
     * @var         array     Variables provided via HTTP POST
     */
    public $__POST;

    /**
     * @var         array     Variables provided via HTTP post file uploads
     */
    public $__FILES;

    /**
     * @var         string    Counter for AJAX events, increased with each event
     */
    private $mEventCount = 0;

    /**
     * Initializes the user
     */
    function createDpLiving()
    {
        $this->addId('user');
        $this->addProperty('is_user');
        $avatar_nr = $this->_getRandAvatarNr();
        $this->addProperty('avatar_nr', $avatar_nr);
        $this->setTitle("User", DPUNIVERSE_TITLE_TYPE_NAME,
            DPUNIVERSE_AVATAR_URL . 'user' . $avatar_nr . '.gif');
        $this->setBody('<img src="' . DPUNIVERSE_AVATAR_URL . 'user'
            . $avatar_nr . '_body.gif" border="0" alt="" align="left" '
            . 'style="margin-right: 15px" />A user.<br />');
    }

    /**
     * Gets a random avatar image number in order to give guests an avatar
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
     * Sets PHP server, request and cookie variables
     *
     * Sets the PHP server, request and cookie variables sent by the user client
     * at its last HTTP request.
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
     * Sends something to dpclient.js running on the user's browser
     *
     * Sends the given data to the user's browser. It's up the user's DutchPIPE
     * Javascript client, dpclient.js by default, what to do with the received
     * content. dpclient.js can be send data like
     * '<message>hello world</message>' and '<window>hello world</window>',
     * to show something in the message area and pop up a window respectively.
     *
     * @param      string    $data      message string
     */
    function tell($data, &$from = NULL)
    {
        if ((FALSE === is_string($data) || 0 === strlen($data)) &&
                (FALSE === is_array($data)) || 0 === count($data)) {
            return;
        }

        if (FALSE === is_null($from)
                && (FALSE === ($env = $this->getEnvironment())
                || $env !== $from)) {
            echo "Message skipped, no longer in page\n";
            return;
        }

        // If this user is the same user doing the current HTTP request, tell
        // straight away. Otherwise, store the message for the next time we get
        // a HTTP request from the user.
        if (TRUE === get_current_dpuniverse()->mNoDirectTell || $this !== get_current_dpuser()) {
            echo "Storing {$this->getTitle()}: "
                . (strlen($data) > 512 ? substr($data, 0, 512) : $data)
                . "\n";
            get_current_dpuniverse()->storeTell($this, $data, $from);
            return;
        }
        if (is_array($data)) {

            $data = $data[$this->getProperty('display_mode')];
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
        if ($data !== '1'
                || !isset($universe->mrCurrentDpUserRequest->mToldSomething)
                || FALSE ===
                $universe->mrCurrentDpUserRequest->mToldSomething) {

            if ($mtype_start !== '') {
                $data = "<$mtype_start>$data</$mtype_end>";
            }
            if ($data !== '1') {
                echo "Telling {$this->getTitle()}: "
                    . (strlen($data) > 512 ? substr($data, 0, 512) : $data)
                    . "\n";
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
}
?>