<?php
/**
 * In Front Of A House
 *
 * DutchPIPE version 0.3; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: alley.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * In Front Of A House
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */
final class Alley extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dptext('In Front Of A House');
        $this->addItem(explode('#', dptext('door#simple door')),
            dptext('A simple door. It is %s.'), 'getDoorStatusStr',
            'door_area');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dptext('Showcases'), DPUNIVERSE_PAGE_PATH . 'showcases.php'));
        $this->setMapArea('alley_map', 'door_area', 'rect', '142,223,196,274');
        $this->addExit(explode('#', dptext('sw#square')),
            DPUNIVERSE_PAGE_PATH . 'square.php', NULL,
            array('alley_map', 'square_area', 'rect', '25,311,400,336'));

        $this->houseObj = new_dp_property(FALSE);
        $this->isDoorUnlocked = new_dp_property(FALSE);
        $this->isDoorUnlocked = FALSE;
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        if ($this->isDoorUnlocked) {
            $this->isDoorUnlocked = FALSE;
        }
    }

    function isExitOpened()
    {
        if (!$this->isDoorUnlocked) {
            $user = get_current_dpobject();

            $user->tell(dptext("The door of the house is locked.<br />"));
            return FALSE;
        }

        return TRUE;
    }

    public function setIsDoorUnlocked($isDoorUnlocked, $updateHouse = TRUE)
    {
        $this->setDpProperty('isDoorUnlocked', $isDoorUnlocked);

        if ($updateHouse) {
            if (!$this->houseObj) {
                $this->houseObj = get_current_dpuniverse()->getDpObject(
                    DPUNIVERSE_PAGE_PATH . 'house.php');
            }

            $this->houseObj->setIsDoorUnlocked($isDoorUnlocked, FALSE);
        }

        $this->body = '<img src="' . DPUNIVERSE_IMAGE_URL
            . 'alley.jpg" width="450" height="336" border="0"
usemap="#alley_map" style="border: solid 1px black; margin-right: 10px""
title="" alt="" align="left" /><div style="width: 220px; float: left"><br /><b>'
            . dptext('In Front Of A House') . '</b><br /><p align="justify">'
            . (!$isDoorUnlocked
? sprintf(dptext('The stone paved alley ends here in front of a large brick
house.</p><p align="justify">To the <a href="%s">southwest</a> you see a
square.'), DPSERVER_CLIENT_URL . '?location=/page/square.php')
: sprintf(dptext('The stone paved alley ends here in front of a large brick
house.</p><p align="justify">A door leads <a href="%s">inside</a>. To the
<a href="%s">southwest</a> you see a square.'),
            DPSERVER_CLIENT_URL . '?location=/page/house.php',
            DPSERVER_CLIENT_URL . '?location=/page/square.php'))
            . '</p></div><br clear="all" />';

        $this->removeAction(dptext('unlock'), 'door_area');
        $this->removeAction(dptext('lock'), 'door_area');
        $this->removeExit(dptext('n'));

        if (!$isDoorUnlocked) {
            $this->addAction(dptext('unlock door'), dptext('unlock'), 'actionUnlock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
                'door_area', dptext('unlock door'));
            $this->addAction(dptext('lock'), dptext('lock'), 'actionLock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
            $this->addExit(explode('#',
                dptext('n#house#enter house#inside#door#enter door')),
                DPUNIVERSE_PAGE_PATH . 'house.php', 'isExitOpened');
        } else {
            $this->addAction(dptext('unlock'), dptext('unlock'), 'actionUnlock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
            $this->addAction(dptext('lock door'), dptext('lock'), 'actionLock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
                'door_area', dptext('lock door'));
            $this->addExit(explode('#',
                dptext('n#house#enter house#inside#door#enter door')),
                DPUNIVERSE_PAGE_PATH . 'house.php',
                'isExitOpened', 'door_area', dptext('enter house'));
        }

        $this->tell('<changeDpElement id="dppage_body">' . $this->getBody()
            . '</changeDpElement>');
    }

    public function actionUnlock($verb, $noun)
    {
        echo "verb: $verb\nnoun: $noun\n";
        if (dptext('door') != $noun && dptext('door with key') != $noun
                && dptext('door with silver key') != $noun) {
            $this->setActionFailure(dptext('Unlock what?'));
            return FALSE;
        }
        $user = get_current_dpuser();

        if (!($key = $user->isPresent(dptext('silver key')))) {
            $user->tell(
                dptext("You don't have the right key to unlock the door.<br />"));
            return TRUE;
        }

        if ($this->isDoorUnlocked) {
            $user->tell(dptext('The door is already unlocked.<br />'));
            return TRUE;
        }

        $user->tell(sprintf(
            dptext('You unlock the door with %s. You can now enter the building.<br />'),
            $key->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $this->tell(ucfirst(sprintf(
            dptext('%s unlocks the door.<br />'),
            $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $user);
        $this->isDoorUnlocked = TRUE;

        return TRUE;
    }

    public function actionLock($verb, $noun)
    {
        if (dptext('door') != $noun && dptext('door with key') != $noun
                && dptext('door with silver key') != $noun) {
            $this->setActionFailure(dptext('Lock what?'));
            return FALSE;
        }

        $user = get_current_dpuser();

        if (!($key = $user->isPresent(dptext('silver key')))) {
            $user->tell(
                dptext("You don't have the right key to lock the door.<br />"));
            return TRUE;
        }

        if (!$this->isDoorUnlocked) {
            $user->tell(dptext('The door is already locked.<br />'));
            return TRUE;
        }

        $user->tell(sprintf(dptext('You lock the door with %s.<br />'),
            $key->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
        $this->tell(ucfirst(sprintf(
            dptext('%s locks the door.<br />'),
            $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $user);
        $this->isDoorUnlocked = FALSE;

        return TRUE;
    }

    public function getDoorStatusStr($item)
    {
        return $this->isDoorUnlocked ? dptext('unlocked') : dptext('locked');
    }
}
?>
