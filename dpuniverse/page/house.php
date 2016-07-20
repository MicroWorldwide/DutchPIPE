<?php
/**
 * Inside The House
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
 * @version    Subversion: $Id: house.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * Inside The House
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
final class House extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dptext('Inside The House');
        $this->addItem(explode('#', dptext('door#simple door')),
            dptext('A simple door. It is %s.'), 'getDoorStatusStr');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dptext('Showcases'), DPUNIVERSE_PAGE_PATH . 'showcases.php'));
        $this->setMapArea('house_map', 'door_area', 'poly',
            '267,187,270,39,337,40,332,188,267,187');
        $this->setMapArea('house_map', 'chest_area', 'poly',
            '353,264,355,218,425,216,448,226,444,273,375,284,353,264');
        $this->addItem(explode('#', dptext('chest#wooden chest')),
            dptext("A wooden chest. You wonder what's inside. It is %s."),
            'getChestStatusStr', 'chest_area');
        $this->addExit(explode('#',
            dptext('s#alley#enter alley#door#enter door#out#exit#leave#leave house')),
            DPUNIVERSE_PAGE_PATH . 'alley.php', 'isExitOpened', 'door_area',
            dptext('leave house'));

        $this->alleyObj = new_dp_property(FALSE);
        $this->isDoorUnlocked = new_dp_property(FALSE);
        $this->isDoorUnlocked = FALSE;
        $this->isChestUnlocked = new_dp_property(FALSE);
        $this->isChestUnlocked = FALSE;
        $this->isChestSearched = new_dp_property(FALSE);
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
         if ($this->isChestUnlocked) {
            $this->isChestUnlocked = FALSE;
        }
        $this->isChestSearched = FALSE;
    }

    function isExitOpened()
    {
        if (!$this->isDoorUnlocked) {
            get_current_dpobject()->tell(dptext(
                dptext('The door of the house is locked.<br />')));
            return FALSE;
        }

        return TRUE;
    }

    public function setIsDoorUnlocked($isDoorUnlocked, $updateAlley = TRUE)
    {
        if (!$this->alleyObj) {
            $this->alleyObj = get_current_dpuniverse()->getDpObject(
                DPUNIVERSE_PAGE_PATH . 'alley.php');
        }

        if ($updateAlley) {
            $this->alleyObj->setIsDoorUnlocked($isDoorUnlocked, FALSE);
        }

        $this->setHouseBody();

        $this->removeAction(dptext('unlock'), 'door_area');
        $this->removeAction(dptext('lock'), 'door_area');

        if (!$isDoorUnlocked) {
            $this->addAction(dptext('unlock'), dptext('unlock'), 'actionUnlock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
                'door_area', dptext('unlock door'));
            $this->addAction(dptext('lock'), dptext('lock'), 'actionLock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
        } else {
            $this->addAction(dptext('unlock'), dptext('unlock'), 'actionUnlock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
            $this->addAction(dptext('lock'), dptext('lock'), 'actionLock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
                'door_area', dptext('lock door'));
        }

        $this->tell('<changeDpElement id="dppage_body">' . $this->getBody()
            . '</changeDpElement>');
    }

    public function getIsDoorUnlocked()
    {
        if (!$this->alleyObj) {
            $this->alleyObj = get_current_dpuniverse()->getDpObject(
                DPUNIVERSE_PAGE_PATH . 'alley.php');
        }

        return $this->alleyObj->isDoorUnlocked;
    }

    public function setIsChestUnlocked($isChestUnlocked)
    {
        $this->setDpProperty('isChestUnlocked', $isChestUnlocked);
        $this->setHouseBody();
        $this->removeAction(dptext('unlock'), 'chest_area');
        $this->removeAction(dptext('lock'), 'chest_area');
        $this->removeAction(dptext('search'), 'chest_area');

        if (!$isChestUnlocked) {
            $this->addAction(dptext('unlock'), dptext('unlock'), 'actionUnlock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
                'chest_area', dptext('unlock chest'));
            $this->addAction(dptext('lock'), dptext('lock'), 'actionLock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
            $this->addAction(dptext('search'), dptext('search'), 'actionSearch',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_DISABLED, DP_ACTION_SCOPE_INVENTORY,
                'chest_area',
                dptext('search chest'));
        } else {
            $this->addAction(dptext('unlock'), dptext('unlock'), 'actionUnlock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
            $this->addAction(dptext('lock'), dptext('lock'), 'actionLock',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
                'chest_area', dptext('lock chest'));
            $this->addAction(dptext('search'), dptext('search'), 'actionSearch',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
                'chest_area',
                dptext('search chest'));
        }
        $this->tell('<changeDpElement id="dppage_body">' . $this->getBody()
            . '</changeDpElement>');
    }

    public function setHouseBody()
    {
        $this->setBody('<img src="' . DPUNIVERSE_IMAGE_URL
. (!$this->isChestUnlocked ? 'house_chestclosed.jpg' : 'house_chestopen.jpg')
. '" width="450" height="338" border="0" usemap="#house_map" alt=""
style="border: solid 1px black; margin-right: 10px" title="" alt="" align="left" />
<div style="width: 220px; float: left">
<br /><b>' . dptext('Inside The House') . '</b><br />'
. (!$this->isDoorUnlocked
? dptext('<p align="justify">You\'re inside the house. It looks cosy and
warm.</p><p align="justify">The door leading outside is locked.</p>')
: sprintf(dptext('<p align="justify">You\'re inside the house. It looks cosy and
warm.</p><p align="justify">The exit leads <a href="%s">outside</a> to the
alley.</p>'), DPSERVER_CLIENT_URL . '?location=/page/alley.php'))
. '</div><br clear="all" />', 'text');
    }

    public function actionUnlock($verb, $noun)
    {
        $user = get_current_dpobject();

        $door_str = $key_str = $key_obj = NULL;

        if ($noun) {
            if (!preg_match(dptext('/^(.+)\swith\s(.+)$/'), $noun, $matches)) {
                $door_str = $noun;
            } else {
                $door_str = $matches[1];
                $key_str = $matches[2];
            }
        }

        if (is_null($door_str)) {
            $user->setActionFailure(dptext('Unlock what?<br />'));
            return FALSE;
        }

        if (!is_null($key_str)) {
            if (!($key_obj = $user->isPresent($key_str))) {
                $user->tell(sprintf(dptext("You don't have a %s<br />"),
                    $key_str));
                return TRUE;
            }
        }

        if (dptext('door') == $door_str) {
            if (!is_null($key_obj)) {
                if (!$key_obj->isId(dptext('silver key'))) {
                    $user->tell(ucfirst(sprintf(dptext("%s doesn't fit.<br />"),
                        $key_obj->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));
                    return TRUE;
                }
            } elseif (!$user->isPresent(dptext('silver key'))) {
                $user->tell(dptext("You don't have the right key to unlock the door.<br />"));
                return TRUE;
            }

            if ($this->isDoorUnlocked) {
                $user->tell(dptext('The door is already unlocked.<br />'));
                return TRUE;
            }

            $user->tell(
                dptext('You unlock the door with the silver key.<br />'));
            $this->tell(ucfirst(sprintf(
                dptext('%s unlock the door.<br />'),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
                $user);
            $this->isDoorUnlocked = TRUE;

            return TRUE;
        }

        if (dptext('chest') == $door_str
                || dptext('wooden chest') == $door_str) {
            if (!is_null($key_obj)) {
                if (!$key_obj->isId(dptext('golden key'))) {
                    $user->tell(sprintf(dptext("%s doesn't fit.<br />"),
                        $key_obj->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
                    return TRUE;
                }
            } elseif (!$user->isPresent(dptext('golden key'))) {
                $user->tell(dptext("You don't have the right key to unlock the wooden chest.<br />"));
                return TRUE;
            }

            if ($this->isChestUnlocked) {
                $user->tell(dptext('The wooden chest is already unlocked.<br />'));
                return TRUE;
            }

            $user->tell(dptext('You unlock the wooden chest with the golden key.<br />'));
            $this->tell(ucfirst(sprintf(
                dptext('%s unlocks the wooden chest.<br />'),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
                $user);
            $this->isChestUnlocked = TRUE;

            return TRUE;
        }

        $user->setActionFailure(dptext("You can't unlock that.<br />"));
        return FALSE;
    }

    public function actionLock($verb, $noun)
    {
        $user = get_current_dpobject();

        $door_str = $key_str = $key_obj = NULL;

        if ($noun) {
            if (!preg_match(dptext('/^(.+)\swith\s(.+)$/'), $noun, $matches)) {
                $door_str = $noun;
            } else {
                $door_str = $matches[1];
                $key_str = $matches[2];
            }
        }

        if (is_null($door_str)) {
            $user->setActionFailure(dptext('Lock what?<br />'));
            return FALSE;
        }

        if (!is_null($key_str)) {
            if (!($key_obj = $user->isPresent($key_str))) {
                $user->tell(sprintf(dptext("You don't have a %s<br />"),
                    $key_str));
                return TRUE;
            }
        }

        if (dptext('door') == $door_str) {
            if (!is_null($key_obj)) {
                if (!$key_obj->isId(dptext('silver key'))) {
                    $user->tell(sprintf(dptext("%s doesn't fit.<br />"),
                        $key_obj->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
                    return TRUE;
                }
            } elseif (!$user->isPresent(dptext('silver key'))) {
                $user->tell(dptext("You don't have the right key to lock the door.<br />"));
                return TRUE;
            }

            if (!$this->isDoorUnlocked) {
                $user->tell(dptext('The door is already locked.<br />'));
                return TRUE;
            }

            $user->tell(dptext('You lock the door with the silver key.<br />'));
            $this->tell(ucfirst(sprintf(
                dptext('%s lock the door.<br />'),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
                $user);
            $this->isDoorUnlocked = FALSE;

            return TRUE;
        }

        if (dptext('chest') == $door_str
                || dptext('wooden chest') == $door_str) {
            if (!is_null($key_obj)) {
                if (!$key_obj->isId(dptext('golden key'))) {
                    $user->tell(sprintf(dptext("%s doesn't fit.<br />"),
                        $key_obj->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
                    return TRUE;
                }
            } elseif (!$user->isPresent(dptext('golden key'))) {
                $user->tell(dptext("You don't have the right key to lock the wooden chest.<br />"));
                return TRUE;
            }

            if (!$this->isChestUnlocked) {
                $user->tell(
                    dptext('The wooden chest is already locked.<br />'));
                return TRUE;
            }

            $this->isChestUnlocked = FALSE;
            $user->tell(dptext('You lock the wooden chest with the golden key.<br />'));
            $this->tell(ucfirst(sprintf(
                dptext('%s locks the wooden chest.<br />'),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
                $user);

            return TRUE;
        }

        $user->setActionFailure(dptext("You can't lock that.<br />"));
        return FALSE;
    }

    public function actionSearch($verb, $noun)
    {
        if (FALSE === in_array($noun, explode('#',
                dptext('chest#wooden chest#the chest#the wooden chest')))) {
            return FALSE;
        }

        $user = get_current_dpobject();

        if (!$this->isChestUnlocked) {
            $user->tell(dptext('The chest is closed and locked.<br />'));
            return TRUE;
        }

        if ($this->isChestSearched) {
            $user->tell(dptext('You find nothing.<br />'));
            return TRUE;
        }

        $this->isChestSearched = TRUE;
        $this->credits += 735;
        $user->tell(
            dptext('You search the wooden chest and find some credits!<br />'));
        $this->tell(ucfirst(sprintf(
            dptext('%s searches the wooden chest and finds some credits!<br />'),
            $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))),
            $user);

        return TRUE;
    }

    public function getDoorStatusStr($item)
    {
        return $this->isDoorUnlocked ? dptext('unlocked') : dptext('locked');
    }

    public function getChestStatusStr($item)
    {
        return $this->isChestUnlocked ? dptext('unlocked') : dptext('locked');
    }
}
?>
