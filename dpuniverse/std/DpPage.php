<?php
/**
 * A DutchPIPE enabled web page
 *
 * DutchPIPE version 0.2; PHP version 5
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
 * @version    Subversion: $Id: DpPage.php 243 2007-07-08 16:26:23Z ls $
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
 * Creates the following DutchPIPE properties:<br />
 *
 * - boolean <b>isPage</b> - Set to TRUE
 * - boolean|integer <b>isMovingArea</b> - Set to FALSE, can be type 1 or 2
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
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
     * Aliases used for exits, e.g. 'enter bar' for 'north'
     *
     * @var        array
     * @access     private
     */
    private $mExitAliases = array();

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
    final function createDpObject()
    {
        $this->setTitleType(DPUNIVERSE_TITLE_TYPE_NAME);
        $this->addId(dptext('page'));
        $this->addExit(dptext('login'), DPUNIVERSE_PAGE_PATH . 'login.php');

        $this->isPage = new_dp_property(TRUE);
        $this->isMovingArea = new_dp_property(FALSE);

        $this->createDpPage();
    }

    /**
     * Creates this object
     *
     * @see        createDpObject
     */
    function createDpPage()
    {
    }

    /**
     * Creates this page
     *
     * Calls the method 'createDpPage' in this page, if it exists.
     */
    final function resetDpObject()
    {
        $this->resetDpPage();
    }

    /**
     * Creates this object
     *
     * @see        createDpObject
     */
    function resetDpPage()
    {
    }

    function eventDpObject($name)
    {
        $args = func_get_args();
        call_user_func_array(array($this, 'eventDpPage'), $args);
    }

    function eventDpPage($name)
    {
    }

    /**
     * Adds an "exit" out of this page
     *
     * Exits are links that can be typed on the command line or used by computer
     * controlled character to wander around the site. Adding compass directions
     * like 'nw' will also add the full 'northwest' action, and vice versa, if
     * not defined.
     *
     * @param       string    $direction    Command to use link, "home", "bar"
     * @param       array     $direction    Multiple directions, "bar", "north"
     * @param       string    $destination  URL
     */
    final function addExit($direction, $destination, $method = NULL,
        $mapArea = NULL, $mapAreaActionTitle = NULL)
    {
        $short_dirs = array('n', 'ne', 'e', 'se', 's', 'sw', 'w', 'nw');
        $long_dirs = array('north', 'northeast', 'east', 'southeast', 'south',
            'southwest', 'west', 'northwest');

        if (empty($direction)) {
            return;
        }

        if (!is_array($direction)) {
            $direction = array($direction);
        }

        if (FALSE !== ($x = array_search($direction[0], $short_dirs))) {
            if (FALSE !== ($y = array_search($long_dirs[$x], $direction))) {
                $direction[$y] = $direction[0];
            }
            $direction[0] = $long_dirs[$x];

            //print_r($direction);
            $test = true;
        }

        $newdirs = array();
        foreach ($direction as $dir) {
            if (FALSE !== ($x = array_search($dir, $short_dirs))) {
                if (FALSE === ($y = array_search($long_dirs[$x], $direction))
                        && FALSE === ($y = array_search($long_dirs[$x],
                        $newdirs))) {
                    $newdirs[] = $long_dirs[$x];
                }
            } elseif (FALSE !== ($x = array_search($dir, $long_dirs))) {
                if (FALSE === ($y = array_search($short_dirs[$x], $direction))
                        && FALSE === ($y = array_search($short_dirs[$x],
                        $newdirs))) {
                    $newdirs[] = $short_dirs[$x];
                }
            }
        }

        $direction = array_merge($direction, $newdirs);
        //if (isset($test)) { print_r($direction); exit; }

        $tmp = $direction[0];
        if (1 < ($sz = sizeof($direction))) {
            for ($i = 1; $i < $sz; $i++) {
                $this->mExitAliases[$direction[$i]] = $tmp;
            }
        }

        $this->mExits[$direction[0]] = array($destination, $method);

        if (!is_null($mapArea) && is_null($mapAreaActionTitle)) {
            $mapAreaActionTitle = $direction[0];
        }

        $this->addAction($mapAreaActionTitle, $direction, 'useExit',
            DP_ACTION_OPERANT_NONE, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
            $mapArea);
    }

    function removeExit($direction)
    {
        if (isset($this->mExitAliases[$direction])) {
            $this->removeAction($this->mExitAliases[$direction]);
            return;
        }
        if (isset($this->mExits[$direction])) {
            $this->removeAction($direction);
            unset($this->mExits[$direction]);
            foreach ($this->mExitAliases as $dir_alias => $dir) {
                if ($direction === $dir) {
                    unset($this->mExitAliases[$dir_alias]);
                }
            }
        }
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
        foreach ($exits as $direction => $destination) {
            if (!is_array($destination)) {
                $this->addExit($direction, $destination);
            } else {
                array_unshift($destination, $direction);
                call_user_method_array('addExit', $this, $destination);
            }
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
    final function getExitDestination($direction)
    {
        if (isset($this->mExitAliases[$direction])) {
            $direction = $this->mExitAliases[$direction];
        }
        return !isset($this->mExits[$direction]) ? FALSE :
            $this->mExits[$direction][0];
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
    final function getExitMethod($direction)
    {
        if (isset($this->mExitAliases[$direction])) {
            $direction = $this->mExitAliases[$direction];
        }
        return !isset($this->mExits[$direction]) ? FALSE :
            $this->mExits[$direction][1];
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
    final function getExitMapArea($direction)
    {
        if (isset($this->mExitAliases[$direction])) {
            $direction = $this->mExitAliases[$direction];
        }
        return !isset($this->mExits[$direction]) ? FALSE :
            $this->mExits[$direction][2];
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

    final function getExitAliases()
    {
        return (array)$this->mExitAliases;
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
        if (!($method = $this->getExitMethod($verb))
                || $this->{$method}($verb)) {
            get_current_dpobject()->tell('<location>'
                . $this->getExitDestination($verb) . '</location>');
        }
        return TRUE;
    }

    /**
     * Sends a message to all objects in this page, "makes sound or movement"
     *
     * Calls the tell method in all objects in this page with the message.
     * One or more extra arguments can be given to specify objects which should
     * be skipped. For example:
     *
     *     $user->tell('You smile happily.<br />');
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
                    if (isset($data[$ob->displayMode])
                            && ($tmp =
                            $data[$ob->displayMode])) {
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
                            && isset($data[$ob->displayMode])
                            && ($tmp =
                            $data[$ob->displayMode])) {
                        $ob->tell($tmp, $this);
                    }
                }
            }
        }
        elseif (func_num_args() > 2)  {
            $from = array_slice(func_get_args(), 1);
            if (FALSE === is_array($data)) {
                foreach ($inv as &$ob) {
                    if (FALSE === in_array($ob, $from, TRUE)) {
                        $ob->tell($data, $this);
                    }
                }
            } else {
                foreach ($inv as &$ob) {
                    if (FALSE === in_array($ob, $from, TRUE)
                            && isset($data[$ob->displayMode])
                            && ($tmp =
                            $data[$ob->displayMode])) {
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

        foreach ($this->mNavigationTrail as $link) {
            if (is_array($link)) {
                if ($link[0] === DPUNIVERSE_NAVLOGO) {
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
     * @return     array     navigation trail elements
     */
    function getNavigationTrail()
    {
        return $this->mNavigationTrail;
    }

    /**
     * Gets HTML with a navigation trail for this page
     *
     * @return     string    HTML for navigation trail
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

    /**
     * Gets HTML with a navigation trail element for this page
     *
     * @access     private
     * @param      mixed     $navitem   string or string/destination pairs
     * @return     string    HTML for navigation trail element
     * @see        getNavigationTrailHtml
     */
    function _getNavigationTrailHtmlElement($navitem)
    {
        if (FALSE === is_array($navitem)) {
            return $navitem;
        }

        if (strlen($navitem[1]) >= 6 && substr($navitem[1], 0, 6) == 'uri://') {
            $link = substr($navitem[1], 6);
        } else {
            $link = $navitem[1] == '' ? DPUNIVERSE_WWW_URL
                : DPSERVER_CLIENT_URL . '?location=' . $navitem[1];
        }

        return $navitem[0] <> DPUNIVERSE_NAVLOGO
            ? '<div id="navlink"><a class="navtrail" href="' . $link . '">'
                . $navitem[0] . '</a></div>'
            : '<div id="navlink"><a class="navtrail" href="' . $link
                . '" style="cursor: pointer">' . dptext(DPUNIVERSE_NAVLOGO)
                . '</a></div>';
    }
}
?>
