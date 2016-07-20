<?php
/**
 * A DutchPIPE enabled web page
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
 * @version    Subversion: $Id: DpPage.php 45 2006-06-20 12:38:26Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */

/**
 * Builts upon the standard DpObject class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpObject.php');

/**
 * A DutchPIPE enabled web page
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpPage extends DpObject
{
    /**
     * @var         array     All "exits" out of this page
     * @access      private
     */
    private $mExits = array();

    /**
     * @var         array     Elements to make a navigation trail
     * @access      private
     */
    private $mNavigationTrail = array();

    /**
     * Creates this page
     *
     * Calls the method 'createDpPage' in this page, if it exists.
     */
    function createDpObject()
    {
        $this->setTitleType(DPUNIVERSE_TITLE_TYPE_NAME);
        $this->addId(dptext('page'));
        $this->addProperty('is_page');
        $this->addExit(dptext('login'), DPUNIVERSE_PAGE_PATH . 'login.php');
        if (method_exists($this, 'createDpPage')) {
            $this->createDpPage();
        }
    }

    /**
     * Adds an "exit" out of this page
     *
     * Exits are links that can be typed on the command line or used by computer
     * controlled character to wander around the site.
     *
     * @param       string    $direction    Command to use link, "home", "bar"
     * @param       string    $destination  URL
     */
    final function addExit($direction, $destination)
    {
        $this->mExits[$direction] = $destination;
        $this->addAction($direction, $direction, 'useExit',
            DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
    }

    /**
     * Sets all "exits" out of this page at once
     *
     * Exits are links that can be typed on the command line or used by computer
     * controlled character to wander around the site.
     *
     * @param       array     $exits      Direction/destination pairs
     */
    final function setExits($exits)
    {
        $this->mExits = array();
        foreach ($this->mExits as $direction => $destination) {
            $this->addExit($direction, $destination);
        }
    }

    /**
     * Gets URL of "exit" out of this page
     *
     * Exits are links that can be typed on the command line or used by computer
     * controlled character to wander around the site.
     *
     * @param      string    $direction   Command to use link, "home", "bar"
     * @return     string                 URL
     */
    final function getExit($direction)
    {
        return $this->mExits[$direction];
    }

    /**
     * Gets all "exits" out of this page
     *
     * Exits are links that can be typed on the command line or used by computer
     * controlled character to wander around the site.
     *
     * @return     array                 Direction/destination pairs
     */
    final function getExits()
    {
        return (array)$this->mExits;
    }

    /**
     * Makes the active object exit this page and go to a new URL
     *
     * @param   string  $verb   The name or verb part of the action, "home"
     * @param   string  $noun   The given id, noun or remainder, usually empty
     * @return  boolean         FALSE in case of failure, TRUE for success
     */
    function useExit($verb, $noun = '')
    {
        get_current_dpobject()->tell('<location>' . $this->getExit($verb)
            . '</location>');
        return TRUE;
    }

    /**
     * Sends a message to all objects in this page, "makes sound or movement"
     *
     * Calls the tell method in all objects in this page with the message.
     * One or more extra arguments can be given to specify objects which should
     * be skipped. For example:
     *
     *     $user->tell('You smile happily.<br />';
     *     $user->getEnvironment()->tell(ucfirst(
     *         $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))
     *         . ' smiles happily.<br />', $user);
     *
     * @param      string    $data      message string
     * @param      object    &$from     First object to skip
     * @param      object    &$from2    Second object to skip, etc.
     */
    function tell($data, &$from = NULL)
    {
        // There's nothing here:
        if (!sizeof($inv = $this->getInventory())) {
            return;
        }

        if (func_num_args() <= 1)  {
            if (FALSE === is_array($data)) {
                foreach ($inv as &$ob) {
                    $ob->tell($data, $this);
                }
            } else {
                foreach ($inv as &$ob) {
                    if (isset($data[$ob->getProperty('display_mode')])
                            && ($tmp =
                            $data[$ob->getProperty('display_mode')])) {
                        $ob->tell($tmp, $this);
                    }
                }
            }
        }
        elseif (func_num_args() == 2)  {
            $from = func_get_arg(1);
            if (FALSE === is_array($data)) {
                foreach ($inv as &$ob) {
                    if ($ob !== $from) {
                        $ob->tell($data, $this);
                    }
                }
            } else {
                foreach ($inv as &$ob) {
                    if ($ob !== $from
                            && isset($data[$ob->getProperty('display_mode')])
                            && ($tmp =
                            $data[$ob->getProperty('display_mode')])) {
                        $ob->tell($tmp, $this);
                    }
                }
            }
        }
        elseif (func_num_args() > 2)  {
            $from = array_slice(func_get_args(), 1);
            if (FALSE === is_array($data)) {
                foreach ($inv as &$ob) {
                    if (FALSE === in_array($ob, $from)) {
                        $ob->tell($data, $this);
                    }
                }
            } else {
                foreach ($inv as &$ob) {
                    if (FALSE === in_array($ob, $from)
                            && isset($data[$ob->getProperty('display_mode')])
                            && ($tmp =
                            $data[$ob->getProperty('display_mode')])) {
                        $ob->tell($tmp, $this);
                    }
                }
            }
        }
    }

    /**
     * Sets data to later generate a HTML navigation trail for this page
     *
     * Each item is either a string with the element's title for the last
     * tiem in the navigation trail, or an array with each key such a title,
     * and each value the destination path within dpuniverse.
     *
     * @param      mixed     $navitem   string or string/destination pairs
     */
    function setNavigationTrail()
    {
        if (0 === func_num_args()) {
            return;
        }
        $this->mNavigationTrail = func_get_args();

        $navlogo = dptext(DPUNIVERSE_NAVLOGO);
        foreach ($this->mNavigationTrail as $link) {
            if (is_array($link)) {
                if ($link[0] === $navlogo) {
                    $link[0] = dptext('home');
                }
                $link[0] = explode(' ', $link[0]);
                $link[0] = strtolower($link[0][0]);
                $this->addExit($link[0], $link[1]);
            }
        }
    }

    /**
     * Gets data for the navigation trail for this page, if any
     *
     * @return  array           navigation trail elements
     */
    function getNavigationTrail()
    {
        return $this->mNavigationTrail;
    }

    /**
     * Gets HTML with a navigation trail for this page
     *
     * @return  string          HTML for navigation trail
     */
    function getNavigationTrailHtml()
    {
        if (0 === sizeof($this->mNavigationTrail)) {
            return '<div id="navlink">' . dptext(DPUNIVERSE_NAVLOGO) . '</div>';
        }
        $trail = array();
        foreach ($this->mNavigationTrail as $navitem) {
            $trail[] = $this->_getNavigationTrailHtmlElement($navitem);
        }
        return implode(' <img src="' . DPUNIVERSE_IMAGE_URL
            . 'arr3w.gif" width="6" height="11" border="0" alt=" &gt; " '
            . 'class="arrnav" /> ', $trail);
    }

    function _getNavigationTrailHtmlElement($navitem)
    {
        if (FALSE === is_array($navitem)) {
            return $navitem;
        }

        $link = $navitem[1] == '/' ? '/' : DPSERVER_CLIENT_URL . '?location='
            . $navitem[1];

        return $navitem[0] <> DPUNIVERSE_NAVLOGO
            ? '<div id="navlink"><a class="navtrail" href="' . $link . '">'
                . $navitem[0] . '</a></div>'
            : '<div id="navlink"><a class="navtrail" href="' . $link
                . '" style="cursor: pointer">' . dptext(DPUNIVERSE_NAVLOGO)
                . '</a></div>';
    }
}
?>
