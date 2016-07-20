<?php
/**
 * The Shop's Store
 *
 * DutchPIPE version 0.1; PHP version 5
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
 * @version    Subversion: $Id$
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * The Shop's Store
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.0
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */
final class Store extends DpPage
{
    private $mrSilverKeyObj;

    private $mrTrophyObj;

    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dptext("The Shop's Store");
        $this->setBody(sprintf(dptext('You are in the store of the shop. You can
leave <a href="%s">west</a> to the shop.<br />'),
            DPSERVER_CLIENT_URL . '?location=/page/shop.php'));
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dptext('Showcases'), DPUNIVERSE_PAGE_PATH . 'showcases.php'));
        $this->addExit(explode('#', dptext('w#shop#out#exit')),
            DPUNIVERSE_PAGE_PATH . 'shop.php');
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        if (is_null($this->mrSilverKeyObj)
                || !$this->isPresent($this->mrSilverKeyObj)) {
            // Creates a silver key based on the standard DpObject, moves it
            // here:
            $this->mrSilverKeyObj = get_current_dpuniverse()->newDpObject(
                DPUNIVERSE_STD_PATH . 'DpObject.php');
            $this->mrSilverKeyObj->addId(dptext('key'));
            $this->mrSilverKeyObj->title = dptext('silver key');
            $this->mrSilverKeyObj->titleDefinite = dptext('the silver key');
            $this->mrSilverKeyObj->titleIndefinite = dptext('a silver key');
            $this->mrSilverKeyObj->titleImg = DPUNIVERSE_IMAGE_URL
                . 'silver_key.png';
            $this->mrSilverKeyObj->body = '<img src="' . DPUNIVERSE_IMAGE_URL
                . 'silver_key.png" width="44" height="100" border="0" '
                . 'alt="" align="left" style="margin-right: 15px" />'
                . dptext('A plain silver key.<br />You wonder what it can unlock...');
            $this->mrSilverKeyObj->value = 50;
            $this->mrSilverKeyObj->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            $this->mrSilverKeyObj->weight = 1;
            $this->mrSilverKeyObj->volume = 1;
            $this->mrSilverKeyObj->moveDpObject($this);
        }

        if (is_null($this->mrTrophyObj) ||
                !$this->isPresent($this->mrTrophyObj)) {
            // Creates a trophy based on the standard DpObject, moves it here:
            $this->mrTrophyObj = get_current_dpuniverse()->newDpObject(
                DPUNIVERSE_STD_PATH . 'DpObject.php');
            $this->mrTrophyObj->addId(explode('#',
                dptext('trophy#dutchpipe trophy')));
            $this->mrTrophyObj->title = dptext('DutchPIPE trophy');
            $this->mrTrophyObj->titleDefinite = dptext('the DutchPIPE trophy');
            $this->mrTrophyObj->titleIndefinite = dptext('a DutchPIPE trophy');
            $this->mrTrophyObj->titleImg = DPUNIVERSE_IMAGE_URL . 'trophy.gif';
            $this->mrTrophyObj->body = '<img src="' . DPUNIVERSE_IMAGE_URL
                . 'trophy_body.gif" width="129" height="161" border="0" '
                . 'alt="" align="left" style="margin-right: 15px" />'
                . dptext('A shiny trophy earned on a DutchPIPE showcase.');
            $this->mrTrophyObj->value = 526;
            $this->mrTrophyObj->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            $this->mrTrophyObj->weight = 3;
            $this->mrTrophyObj->volume = 2;
            $this->mrTrophyObj->moveDpObject($this);
        }
    }

    /**
     * Gets the HTML "appearance" of this object
     *
     * Gets HTML to represent the object to another objects. That is, other
     * objects call this method in order to "see" it, and HTML is returned. How
     * an object is seen depends on how the object is related to the object that
     * is viewing it in terms of "physical" location.
     *
     * In other words, a level of 0 means this object is seen by something in
     * its inventory (a user sees a page). Level 1 means this object is seen by
     * an object in its environment (a user sees another user). Level 2 means
     * this object is in the inventory of the object that is seeing it.
     *
     * @param      int       $level           level of visibility
     * @param      boolean   $include_div     include div with id around HTML?
     * @param      object    $from            experimental
     * @param      string    $displayMode     'abstract' or 'graphical'
     * @param      boolean   $displayTitlebar display title bar for pages?
     * @return     string    HTML "appearance" of this object
     */
    public function getStoreList($displayMode = 'abstract',
            $elementId = 'dppage')
    {
        $body = $inventory = '';

        $body = '<div id="' . $elementId . '"><div id="' . $elementId
            . '_inner1"><div class="' . $elementId . '_inner2">';

        $inv = $this->getInventory();
        $inventory = '';
        foreach ($inv as &$ob) {
            $inventory .= $this->getStoreItem($ob, TRUE, NULL, $displayMode);
        }
        if ($inventory !== '') {
            $inventory =
                "<div id=\"dpinventory\"><div id=\"{$this->uniqueId}\">"
                . "$inventory</div></div>";
        } else {
            $inventory = dptext('Nothing');
        }

        $body .= $inventory . '</div></div></div>';

        return $body;
    }

    public function getStoreItem(&$ob, $include_div = TRUE,
            $from = NULL, $displayMode = 'abstract',
            $displayTitlebar = TRUE, $elementId = 'dpstore')
    {
        $user = get_current_dpuser();
        $body = $inventory = '';
        $titlebar = '';

        $status = !isset($ob->status) || FALSE === $ob->status
            ? '' : ' (' . $ob->status . ')';

        if (is_null($from)) {
            $from = $user;
        }

        if ($displayMode === 'graphical' && isset($ob->titleImg)) {
            $title_img = '<img src="' . $ob->titleImg
                . '" border="0" alt="" style="cursor: pointer" '
                . 'onClick="get_store_actions(\'' . $this->uniqueId
                . '\', \'' . $ob->uniqueId . '\', event)" /><br />'
                . ucfirst($ob->getTitle(
                DPUNIVERSE_TITLE_TYPE_INDEFINITE)) . $status . ($ob->isNoBuy
                ? '' : '<br />' . sprintf(dptext('%d credits'),
                $this->getBuyValue($ob->value)));
            return FALSE === $include_div ? $title_img
                : '<div id="' . $ob->uniqueId
                . '" class="title_img' . ($from === $ob ? '_me' : '')
                . '">' . $title_img . '</div>';
        }

        $body = $from === $ob ? '<span class="me">'
            . ucfirst($ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
            . $status . '</span>'
            : ucfirst($ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
            . $status;

        $inv = $ob->getInventory();
        foreach ($inv as &$ob2) {
            $inventory .= $ob2->getAppearance(1, $include_div, $from,
                $displayMode);
        }

        return FALSE === $include_div ? $body . $inventory
            : '<div id="' . $ob->uniqueId
            . '" class="dpobject" onClick="get_actions(\''
            . $ob->uniqueId . '\')">'
            . $body . $inventory . '</div>';
    }

    /**
     * Gets the HTML "appearance" of this object
     *
     * Gets HTML to represent the object to another objects. That is, other
     * objects call this method in order to "see" it, and HTML is returned. How
     * an object is seen depends on how the object is related to the object that
     * is viewing it in terms of "physical" location.
     *
     * In other words, a level of 0 means this object is seen by something in
     * its inventory (a user sees a page). Level 1 means this object is seen by
     * an object in its environment (a user sees another user). Level 2 means
     * this object is in the inventory of the object that is seeing it.
     *
     * @param      int       $level           level of visibility
     * @param      boolean   $include_div     include div with id around HTML?
     * @param      object    $from            experimental
     * @param      string    $displayMode     'abstract' or 'graphical'
     * @param      boolean   $displayTitlebar display title bar for pages?
     * @return     string    HTML "appearance" of this object
     */
    public function getUserList($user, $displayMode = 'abstract',
            $elementId = 'dppage')
    {
        $body = $inventory = '';

        $body = '<div id="' . $elementId . '"><div id="' . $elementId
            . '_inner1"><div class="' . $elementId . '_inner2">';

        $inv = $user->getInventory();
        $inventory = '';
        foreach ($inv as &$ob) {
            $inventory .= $this->getUserItem($ob, TRUE, NULL, $displayMode);
        }
        if ($inventory !== '') {
            $inventory =
                "<div id=\"dpinventory\"><div id=\"{$this->uniqueId}\">"
                . "$inventory</div></div>";
        } else {
            $inventory = dptext('Nothing');
        }

        $body .= $inventory . '</div></div></div>';

        return $body;
    }

    public function getUserItem(&$ob, $include_div = TRUE,
            $from = NULL, $displayMode = 'abstract',
            $displayTitlebar = TRUE, $elementId = 'dpstore')
    {
        $user = get_current_dpuser();
        $body = $inventory = '';
        $titlebar = '';

        $status = !isset($ob->status) || FALSE === $ob->status
            ? '' : ' (' . $ob->status . ')';

        if (is_null($from)) {
            $from = $user;
        }

        if ($displayMode === 'graphical' && isset($ob->titleImg)) {
            $title_img = '<img src="' . $ob->titleImg
                . '" border="0" alt="" style="cursor: pointer" '
                . 'onClick="get_seller_actions(\'' . $this->uniqueId
                . '\', \'' . $ob->uniqueId . '\', event)" /><br />'
                . ucfirst($ob->getTitle(
                DPUNIVERSE_TITLE_TYPE_INDEFINITE)) . $status . ($ob->isNoSell
                ? '' : '<br />' . $this->getSellValue($ob->value) . ' credits');
            return FALSE === $include_div ? $title_img
                : '<div id="' . $ob->uniqueId
                . '" class="title_img' . ($from === $ob ? '_me' : '')
                . '">' . $title_img . '</div>';
        }

        $body = $from === $ob ? '<span class="me">'
            . ucfirst($ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
            . $status . '</span>'
            : ucfirst($ob->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
            . $status;

        $inv = $ob->getInventory();
        foreach ($inv as &$ob2) {
            $inventory .= $ob2->getAppearance(1, $include_div, $from,
                $displayMode);
        }

        return FALSE === $include_div ? $body . $inventory
            : '<div id="' . $ob->uniqueId
            . '" class="dpobject" onClick="get_actions(\''
            . $ob->uniqueId . '\')">'
            . $body . $inventory . '</div>';
    }

    function getBonusValue($value)
    {
        if (0 == $value || !($user = get_current_dpuser())) {
            return 0;
        }

        $bonus = 5;

        return round(($value * $bonus) / 100);
    }

    function getSellValue($value)
    {
        $result = $value + $this->getBonusValue($value);

        return 0 == $result && $value > 0 ? 1 : $result;
    }

    function getBuyValue($value)
    {
        $result = 2 * $value - $this->getBonusValue(2 * $value);

        return 0 == $result && $value > 0 ? 1 : $result;
    }

    /**
     * Tells user the HTML with the action menu for this object
     */
    function getStoreActionsMenu($itemid)
    {
        $user = get_current_dpuser();

        $actions = array(
            dptext('buy') => sprintf(dptext('buy %s'), $itemid),
            dptext('see') => sprintf(dptext('see %s'), $itemid)
        );

        $action_menu = '';
        foreach ($actions as $menu => $fullaction) {
            $action_menu .= '<div class="action_menu" '
                . 'onMouseOver="this.className=\'action_menu_selected\'" '
                . 'onMouseOut="this.className=\'action_menu\'" '
                . 'onClick="send_action2server(\'' . $fullaction . '\')">'
                . $menu . "</div>\n";
        }
        get_current_dpuser()->tell('<actions id="' . $this->uniqueId
            . '">' . $action_menu . '</actions>');
    }

    /**
     * Tells user the HTML with the action menu for this object
     */
    function getSellersActionsMenu($itemid)
    {
        $user = get_current_dpuser();

        $actions = array(
            dptext('sell') => sprintf(dptext('sell %s'), $itemid)
        );

        $action_menu = '';
        foreach ($actions as $menu => $fullaction) {
            $action_menu .= '<div class="action_menu" '
                . 'onMouseOver="this.className=\'action_menu_selected\'" '
                . 'onMouseOut="this.className=\'action_menu\'" '
                . 'onClick="send_action2server(\'' . $fullaction . '\')">'
                . $menu . "</div>\n";
        }
        get_current_dpuser()->tell('<actions id="' . $this->uniqueId
            . '">' . $action_menu . '</actions>');
    }
}
?>
