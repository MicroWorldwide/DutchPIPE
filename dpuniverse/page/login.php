<?php
/**
 * The Login/register/Logout page
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
 * @version    Subversion: $Id: login.php 278 2007-08-19 22:52:25Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * Gets event constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'events.php');

/**
 * The Login/register/Logout page
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
final class Login extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->setTitle(dp_text('Login/register'));
        $this->setBody(dp_text(DPUNIVERSE_PAGE_PATH . 'login.html'), 'file');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, ''),
            dp_text('Login/register'));
    }

    /**
     * Logs out users, gets the HTML content of this object
     *
     * Logs out logged in users when they enter this page with ?act=logout
     * attached to the URL. Then returns DpPage::getBody().
     *
     * @return     string    HTML content of this object
     * @see        DpObject::getBody()
     */
    public function getBody($str = NULL)
    {
        if (($user = get_current_dpuser()) && isset($user->_GET['act'])
                && 'logout' === $user->_GET['act']
                && $user->isRegistered) {
            get_current_dpuniverse()->logoutUser($user);
            return FALSE;
        }
        return DpPage::getBody($str);
    }

    function validateExisting()
    {
        get_current_dpuniverse()->validateExisting(get_current_dpuser());
        return TRUE;
    }

    function validateCaptcha()
    {
        get_current_dpuniverse()->validateCaptcha(get_current_dpuser());
        return TRUE;
    }

    function validateNewUser()
    {
        $x = get_current_dpuser();
        get_current_dpuniverse()->validateNewUser($x);
        return TRUE;
    }
}
?>
