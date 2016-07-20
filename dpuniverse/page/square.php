<?php
/**
 * A Village Square
 *
 * DutchPIPE version 0.4; PHP version 5
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
 * @version    Subversion: $Id: square.php 278 2007-08-19 22:52:25Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * A Village Square
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
final class Square extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->title = dp_text('A Village Square');
        $this->setBody('<img src="' . DPUNIVERSE_IMAGE_URL . 'square.jpg"
width="450" height="330" border="0" usemap="#square_map" alt=""
style="border: solid 1px black; margin-right: 10px" title="" alt=""
align="left" />
<div style="width: 220px; float: left"><br /><b>' . dp_text('A Village Square')
            . '</b><br />'
            . sprintf(dp_text('<p align="justify">You\'re on a village square. A
beautiful fountain catches your attention.</p><p align="justify">To the
<a href="%s">north</a> you see a bar where they serve cold beer and to the
<a href="%s">east</a> is a small shop. An alley leads
<a href="%s">northeast</a>, while a darker alley heads off to the
<a href="%s">northwest</a>.</p>'),
            DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
            . 'bar.php',
            DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
            . 'shop.php',
            DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
            . 'alley.php',
            DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
            . 'alley2.php')
            . '</div><br clear="all" />');
        $this->setMapArea('square_map', 'fountain_area', 'poly',
            '113,288,113,254,136,248,136,226,148,222,148,205,176,205,176,224,'
            . '187,224,187,249,207,251,212,258,212,282,190,290,140,292,113,'
            . '288');
        $this->addItem(dp_text('fountain'),
            dp_text('A beautiful fountain. '), 'getFountainItem',
            'fountain_area');
        $this->addAction(dp_text('search'), dp_text('search'), 'actionSearch',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_INVENTORY,
            'fountain_area',
            dp_text('search fountain'));
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            array(dp_text('Showcases'), DPUNIVERSE_PAGE_PATH
            . 'showcases.php'));
        $this->addExit(explode('#', dp_text('n#bar#enter bar')),
            DPUNIVERSE_PAGE_PATH . 'bar.php', NULL, array('square_map',
            'bar_area', 'poly',
            '37,216,37,182,228,179,229,212,177,213,177,203,147,204,147,213,37,'
            . '216'), dp_text('enter bar'));
        $this->addExit(explode('#', dp_text('e#shop#enter shop')),
            DPUNIVERSE_PAGE_PATH . 'shop.php', NULL, array('square_map',
            'shop_area', 'poly', '435,241,355,213,355,157,437,162,435,241'),
            dp_text('enter shop'));
        $this->addExit(explode('#', dp_text('ne#alley#enter alley')),
            DPUNIVERSE_PAGE_PATH . 'alley.php', NULL, array('square_map',
            'alley_area', 'poly', '240,207,240,187,249,187,292,207,240,207'),
            dp_text('enter alley'));
        $this->addExit(explode('#', dp_text('nw#dark alley#enter dark alley')),
            DPUNIVERSE_PAGE_PATH . 'alley2.php', NULL, array('square_map',
            'alley2_area', 'poly', '7,226,0,226,0,197,22,197,22,217,7,226'),
            dp_text('enter dark alley'));

        $this->fountainSearched = new_dp_property(FALSE);
    }

    /**
     * Called at regular intervals
     */
    public function resetDpPage()
    {
        $this->fountainSearched = FALSE;
    }

    public function actionSearch($verb, $noun)
    {
        if (!($user = get_current_dpobject())) {
            return FALSE;
        }

        if (FALSE === in_array($noun,
                explode('#', dp_text('fountain#the fountain')))) {
            $user->setActionFailure(dp_text('Search what?<br />'));
            return FALSE;
        }

        if ($this->fountainSearched) {
            $user->tell(
                dp_text('You search the fountain but find nothing.<br />'));
            $this->tell(ucfirst(sprintf(
                dp_text("%s searches the fountain but doesn't seem to find anything.<br />"),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))), $user);
                return TRUE;
        }

        $this->fountainSearched = TRUE;
        $user->tell(
            dp_text('You search the fountain and find some credits!<br />'));
        $this->tell(ucfirst(sprintf(
            dp_text("%s searches the fountain and finds some credits!<br />"),
            $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))), $user);
        $this->credits += 100;

        return TRUE;
    }

    public function getFountainItem($item)
    {
        return !$this->fountainSearched
            ? dp_text('You can search it.<br />')
            : dp_text('It look like someone just searched it.<br />');
    }
}
?>
