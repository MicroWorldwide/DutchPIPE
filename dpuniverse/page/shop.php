<?php
/**
 * A Small Shop
 *
 * DutchPIPE version 0.3; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://www.dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_page
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: shop.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * A Small Shop
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
final class Shop extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dptext('A Small Shop');
        $this->setBody(dptext(DPUNIVERSE_PAGE_PATH . 'shop.html'), 'file',
            '<img src="' . DPUNIVERSE_IMAGE_URL . 'shop.jpg" width="352"
height="264" border="0" usemap="#shop_map" alt=""
style="border: solid 1px black; margin-right: 10px" title="" alt=""
align="left" />
<div style="width: 220px; float: left">
<br /><b>' . dptext('Small Shop') . '</b><br />'
            . sprintf(dptext('<p align="justify">You are in a small pawn shop
where you can buy and sell things. Try this
<a href="javascript:send_action2server(\'list\')">list</a> to view all items for
sale. Check out your
<a href="javascript: send_action2server(\'inventory\')">inventory</a> to see
what you can sell.</p><p align="justify">An exit <a href="%s">west</a> leads
back to the village square.</p>',
            DPSERVER_CLIENT_URL . '?location=/page/square.php')
            . '</div><br clear="all" />'), 'text');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dptext('Showcases'), DPUNIVERSE_PAGE_PATH . 'showcases.php'));
        $this->addExit(explode('#',
            dptext('w#square#out#exit#leave#leave shop')),
            DPUNIVERSE_PAGE_PATH . 'square.php', NULL,
            array('shop_map', 'square_area', 'rect',
            '0,20,50,244', dptext('leave shop')), dptext('leave shop'));
        $this->addAction(dptext('list'), dptext('list'), 'actionList',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
        $this->addAction(dptext('sell'), dptext('sell'), 'actionSell',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
        $this->addAction(dptext('buy'), dptext('buy'), 'actionBuy',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);
        $this->addAction(dptext('see'), dptext('see'), 'actionSee',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY);

        $store = get_current_dpuniverse()->getDpObject(DPUNIVERSE_PAGE_PATH
            . 'store.php');
        $store->addAction(dptext('buy'), dptext('buy'), 'actionBuy',
            DP_ACTION_OPERANT_MENU, array($store, DP_ACTION_TARGET_OBJINV),
            DP_ACTION_AUTHORIZED_ALL, array($this, DP_ACTION_SCOPE_INVENTORY));
        $store->addAction(dptext('see'), dptext('see'), 'actionSee',
            DP_ACTION_OPERANT_MENU, array($store, DP_ACTION_TARGET_OBJINV),
            DP_ACTION_AUTHORIZED_ALL, array($this, DP_ACTION_SCOPE_INVENTORY));
    }

    function eventDpPage($name)
    {
        if (EVENT_ENTERED_INV == $name) {
            $who = func_get_arg(1);
            $who->addAction(dptext('sell'), dptext('sell'), 'actionSell',
                DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_OBJINV,
                DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_SELF);
        } elseif (EVENT_LEFT_INV == $name) {
            $who = func_get_arg(1);
            $who->removeAction('sell');
        }
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        $this->makePresent(DPUNIVERSE_NPC_PATH . 'shopkeeper.php');
    }

    public function actionList($verb, $noun)
    {
        $store = get_current_dpuniverse()->getDpObject(DPUNIVERSE_PAGE_PATH
            . 'store.php');

        $inventory = $store->getAppearance(0, TRUE, NULL,
            get_current_dpobject()->displayMode, -1, 'dpobinv');

        $title = dptext('The shop has the following items for sale:');
        get_current_dpobject()->tell(
            "<window name=\"list\">$title$inventory</window>");

        return TRUE;
    }

    function filterAppearance($level, &$from, $appearance, &$user)
    {
        if ($from[0]->getEnvironment() === $user && 1 === $level
                && !$from[0]->isNoSell) {
            $store = get_current_dpuniverse()->getDpObject(DPUNIVERSE_PAGE_PATH
                . 'store.php');

            $appearance .= '<br /><a href="javascript:send_action2server(\''
                . sprintf(dptext('sell %s'), $from[0]->uniqueId) . '\')">'
                . sprintf(dptext('sell for %d credits'),
                $store->getSellValue($from[0]->value)) . '</a>';
        }

        return $appearance;
    }

    public function actionBuy($verb, $noun)
    {
        $user = get_current_dpuser();

        $store = get_current_dpuniverse()->getDpObject(DPUNIVERSE_PAGE_PATH
            . 'store.php');

        if (!($item = $store->isPresent($noun))) {
            $user->tell(sprintf(
                dptext('That item ("%s") is not for sale here.<br />'), $noun));
            return TRUE;
        }

        $price = $store->getBuyValue($item->value);

        if ($user->credits < $price) {
            $user->tell(sprintf(
                dptext("%s costs %d credits, which you don't have.<br />"),
                ucfirst($item->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                $price));
            return TRUE;
        }

        if (TRUE !== ($move_result = $item->moveDpObject($user))) {
            switch ($move_result) {
            case E_MOVEOBJECT_HEAVY:
                $err = sprintf(
                    dptext("You cannot carry %s, it is too heavy. Drop something else first.<br />"),
                    $item->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE));
                break;
            case E_MOVEOBJECT_VOLUME:
                $err = sprintf(
                    dptext("You cannot carry %s, it takes too much volume. Drop something else first.<br />"),
                    $item->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE));
                break;
            default:
                $err = sprintf(
                    dptext("You fail to buy %s as it cannot be moved.<br />"),
                    $item->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE));
            }

            $user->tell($err);
            return TRUE;
        }

        $user->credits -= $price;
        $user->tell(sprintf(dptext("You buy %s for %d credits.<br />"),
            $item->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE), $price));
        if (($env = $user->getEnvironment()) && $env === $this) {
            $this->tell(sprintf(dptext("%s buys %s.<br />"),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                $item->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)), $user);
        }
        $user->tell('<refreshDpWindow name="list" action="' . dptext('list')
            . '">&nbsp;</refreshDpWindow>');
        $user->tell('<refreshDpWindow name="inventory" action="'
            . dptext('inventory') . '">&nbsp;</refreshDpWindow>');
        return TRUE;
    }

    public function actionSell($verb, $noun)
    {
        $user = get_current_dpuser();

        $store = get_current_dpuniverse()->getDpObject(DPUNIVERSE_PAGE_PATH
            . 'store.php');

        if (!($item = $user->isPresent($noun))) {
            $user->tell(sprintf(
                dptext("You don't own that item (\"%s\").<br />"), $noun));
            return TRUE;
        }

        if ($item->isDrink && $item->isFull) {
            $user->tell(dptext("The shop doesn't accept filled glasses and liquids.<br />"));
            return TRUE;
        }

        if (0 == ($price = $store->getSellValue($item->value))) {
            $user->tell(sprintf(
                dptext("%s has no value.<br />"),
                $item->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
            return TRUE;
        }
        if ($item->isNoStore) {
            $item->removeDpObject();
        } elseif ($item->isNoSell
                || TRUE !== ($move_result = $item->moveDpObject($store))) {
            $user->tell(sprintf(dptext("%s cannot be sold.<br />"),
                $item->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
            return TRUE;
        }

        $user->credits += $price;
        $user->tell(sprintf(dptext("You sell %s for %d credits.<br />"),
            $item->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE), $price));
        if (($env = $user->getEnvironment()) && $env === $this) {
            $this->tell(sprintf(dptext("%s sells %s.<br />"),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                $item->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)), $user);
        }
        return TRUE;
    }

    /**
     * Makes this living object examine an object
     *
     * @param   string  $verb       the action, "examine"
     * @param   string  $noun       what to examine, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    function actionSee($verb, $noun)
    {
        $user = get_current_dpuser();

        if (!strlen($noun)) {
            $user->setActionFailure(dptext('See what item for sale?<br />'));
            return FALSE;
        }

        $store = get_current_dpuniverse()->getDpObject(DPUNIVERSE_PAGE_PATH
            . 'store.php');

        if (!($item = $store->isPresent($noun))) {
            $user->tell(sprintf(
                dptext('That item ("%s") is not for sale here.<br />'), $noun));
            return TRUE;
        }

        $user->tell('<window>' . $item->getAppearance(0, TRUE, NULL,
            $this->displayMode, FALSE, 'dpobinv') . '</window>');
        return TRUE;
    }
}
?>
