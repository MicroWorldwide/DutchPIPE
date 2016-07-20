<?php
/**
 * The Shop's Store
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
 * @version    Subversion: $Id: store.php 252 2007-08-02 23:30:58Z ls $
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
 * @version    Release: 0.2.1
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

    function filterAppearance($level, &$from, $appearance, &$user)
    {
        if (1 === $level && !$from[0]->isNoBuy) {
            $appearance .= '<br />' . sprintf(dptext('%d credits'),
                $this->getBuyValue($from[0]->value));
        }

        return $appearance;
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
}
?>
