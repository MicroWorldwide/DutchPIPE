<?php
/**
 * The Login/register/Logout page
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
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: login.php 45 2006-06-20 12:38:26Z ls $
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
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */
final class Login extends DpPage
{
    private $mLastNewUserErrors = array();

    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        // Standard setup calls:
        $this->setTitle(dptext('Login/register'));
        $this->setBody(dptext(DPUNIVERSE_PAGE_PATH . 'login.html'), 'file');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, '/'),
            dptext('Login/register'));
    }

    public function getBody($str = NULL)
    {

        $universe = get_current_dpuniverse();

        if (($user = get_current_dpuser()) && isset($user->_GET['act'])
                && $user->_GET['act'] == 'logout'
                && $user->getProperty('is_registered')) {
            $username = sprintf(dptext('Guest#%d'), $universe->getGuestCnt());
            $universe->guest_counter++;
            $cookie_id = make_random_id();
            $cookie_pass = make_random_id();
            $oldtitle = $user->getTitle();
            $user->tell('<cookie>removeregistered</cookie>');
            $user->_COOKIE[DPSERVER_COOKIE_NAME] = "$cookie_id;$cookie_pass";
            $universe->tellCurrentDpUserRequest("Set-Login: "
                . $user->_COOKIE[DPSERVER_COOKIE_NAME]);
            $user->_GET['username'] = $username;
            $user->removeId($oldtitle);
            $user->addId($username);
            $user->setTitle(ucfirst($username));
            $user->removeProperty('is_registered');
            $user->removeProperty('is_admin');
            $universe->mrCurrentDpUserRequest->mUsername = $username;

            foreach ($universe->mDpUsers as $user_nr => &$u) {
                if ($u[0] === $user) {
                    $universe->mDpUsers[$user_nr][2] = $username;
                    $universe->mDpUsers[$user_nr][3] = $cookie_id;
                    $universe->mDpUsers[$user_nr][4] = $cookie_pass;
                    $universe->mDpUsers[$user_nr][5] = 0;
                }
            }
            $universe->mrCurrentDpUserRequest->mHasMoved = TRUE;
            $universe->mNoDirectTell = TRUE;
            $user->tell('<window><h1>' . sprintf(dptext('Logged out %s'),
                $oldtitle) . '</h1><br />' . dptext('See you later!') . '<br />'
                . dptext('You are now: <b>%s</b>', $user->getTitle())
                . '</b></window>');
            $universe->mNoDirectTell = FALSE;
            $user->tell('<location>' . DPUNIVERSE_PAGE_PATH
                . 'login.php</location>');

            $this->tell(array('abstract' => '<changeDpElement id="'
                . $user->getUniqueId() . '">'
                . $user->getAppearance(1, FALSE) . '</changeDpElement>',
                'graphical' => '<changeDpElement id="'
                . $user->getUniqueId() . '">'
                . $user->getAppearance(1, FALSE, $user, 'graphical')
                . '</changeDpElement>'), $user);
            return FALSE;
        }
        return DpPage::getBody($str);
    }

    function validateExisting()
    {
        $universe = get_current_dpuniverse();

        $this->mLastNewUserErrors = array();
        $user = get_current_dpuser();
        if (!isset($user->_GET['username'])
                || 0 === strlen($user->_GET['username'])) {
            $this->mLastNewUserErrors[] = '<li>'
                . dptext('No username was given') . '</li>';
        }
        if (0 === sizeof($this->mLastNewUserErrors)
                && $user->_GET['username'] === $user->getTitle()) {
            $this->mLastNewUserErrors[] = '<li>'
                . sprintf(dptext('You are already logged in as %s'),
                $user->getTitle()) . '</li>';
        } elseif (!isset($user->_GET['password'])
                || 0 === strlen($user->_GET['password'])) {
            $this->mLastNewUserErrors[] = '<li>'
                . dptext('No password was given') . '</li>';
        }
        if (0 === sizeof($this->mLastNewUserErrors)) {
            $username = addslashes($user->_GET['username']);
            $result = mysql_query("SELECT userUsername, userPassword, "
                . "userCookieId, userCookiePassword FROM Users WHERE "
                . "userUsernameLower='" . strtolower($username) . "'");
            if (empty($result) || !($row = mysql_fetch_array($result))) {
                $this->mLastNewUserErrors[] =
                    '<li>' . dptext('That username doesn\'t exist') . '</li>';
            } else {
                if ($row[1] !== $user->_GET['password']) {
                    $this->mLastNewUserErrors[] = '<li>'
                        . dptext('Invalid password') . '</li>';
                }
            }
        }

        if (sizeof($this->mLastNewUserErrors)) {
            $user->tell('<window styleclass="dpwindow_error"><h1>'
                . dptext('Invalid login') . '</h1><br /><ul>'
                . implode('', $this->mLastNewUserErrors) . '</ul></window>');
            return FALSE;
        }

        $username = $row[0];
        $cookie_id = $row[2];
        $cookie_pass = $row[3];
        $user->tell('<cookie>removeguest</cookie>');
        $user->_COOKIE[DPSERVER_COOKIE_NAME] = "$cookie_id;$cookie_pass";
        $user->tell('<cookie>' . $user->_COOKIE[DPSERVER_COOKIE_NAME] . '</cookie>');
        $user->_GET['username'] = $username;
        $user->addId($user->_GET['username']);
        $user->addId(strtolower($user->_GET['username']));
        $user->setTitle(ucfirst($user->_GET['username']));
        $user->addProperty('is_registered');
        $universe->mrCurrentDpUserRequest->mUsername =
            $user->_GET['username'];
        /* :TODO: Move admin flag to user table in db */
        if ($user->_GET['username'] == 'Lennert') {
            $user->addProperty('is_admin');
        }
        foreach ($universe->mDpUsers as $user_nr => &$u) {
            if ($u[0] === $user) {
                $universe->mDpUsers[$user_nr][2] =
                    $user->_GET['username'];
                $universe->mDpUsers[$user_nr][3] = $cookie_id;
                $universe->mDpUsers[$user_nr][4] = $cookie_pass;
                $universe->mDpUsers[$user_nr][5] = 1;
            }
        }
        $user->tell('<changeDpElement id="username">'
            . $user->_GET['username'] . '</changeDpElement>');

        $user->tell(array('abstract' => '<changeDpElement id="'
            . $user->getUniqueId() . '"><b>'
            . $user->getAppearance(1, FALSE) . '</b></changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '"><b>'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</b></changeDpElement>'));
        $user->tell('<changeDpElement id="loginlink"><a href="'
            . DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
            . 'login.php&amp;act=logout" style="padding-left: 4px">'
            . dptext('Logout') . '</a></changeDpElement>');
        $this->tell(array('abstract' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</changeDpElement>'), $user);
        $user->tell('<window><h1>' . dptext('Welcome back') . '</h1><br />'
            . sprintf(dptext('You are now logged in as: <b>%s</b>'),
            $user->getTitle()) . '</window>');
        return TRUE;
    }

    function validateNewUser()
    {
        $universe = get_current_dpuniverse();

        $user = get_current_dpuser();

        if (FALSE === $this->validUsername($user->_GET['username'],
                $user->_GET['password'], $user->_GET['password2'])) {
            $user->tell('<window styleclass="dpwindow_error"><h1>'
                . dptext('Invalid registration') . '</h1><br />'
                . dptext('Please correct the following errors:') . '<ul>'
                . implode('', $this->mLastNewUserErrors) . '</ul></window>');
            return FALSE;
        } else {
            if (!isset($user->_GET['givencode'])) {
                if (FALSE === ($captcha_id = $universe->getRandCaptcha())) {
                    return TRUE;
                }
                $user->tell('<window><form method="post" onsubmit="return '
                    . 'send_captcha(' . $captcha_id . ')"><div align="center">'
                    . '<img id="captchaimage" src="/dpcaptcha.php?captcha_id='
                    . $captcha_id . '" border="0" alt="" /></div>'
                    . '<br clear="all" />'
                    . dptext('To complete registration, please enter the code you see above:')
                    . '<br /><br /><div align="center" '
                    . 'style="margin-bottom: 5px"><input id="givencode" '
                    . 'type="text" size="6" maxlength="6" value="" /> '
                    . '<input type="submit" value="'
                    . dptext('OK') . '" /></div><br />'
                    . dptext('This system is used to filter software robots from
registrations submitted by individuals. If you are unable to validate the
above code, please <a href="mailto:registration@dutchpipe.org">mail us</a>
to complete registration.') . '</form></window>');
                return FALSE;
            }
            return TRUE;
        }
    }

    function validateCaptcha()
    {
        $universe = get_current_dpuniverse();

        if (FALSE === $this->validateNewUser()) {
            return;
        }
        $user = get_current_dpuser();
        if (!isset($user->_GET['captcha_id'])
                || !isset($user->_GET['givencode']) || FALSE ===
                $universe->validateCaptcha($user->_GET['captcha_id'],
                $user->_GET['givencode'])) {
            if (FALSE === ($captcha_attempts =
                    $user->getProperty('captcha_attempts'))
                    || $captcha_attempts < 2) {
                $user->addProperty('captcha_attempts',
                    FALSE === $captcha_attempts ? 1 : $captcha_attempts + 1);
                return;
            }
            $user->removeProperty('captcha_attempts');
            $user->tell('<window styleclass="dpwindow_error"><h1>'
                . dptext('Failure validating code') . '</h1><br />'
                . dptext('Please try again.') . '</window>');
            return;
        }

        $username = $user->_GET['username'];

        $keys = $vals = array();
        $keys[] = 'userUsername';
        $vals[] = "'" . addslashes($user->_GET['username']) . "'";
        $keys[] = 'userUsernameLower';
        $vals[] = "'" . addslashes(strtolower($user->_GET['username']))
            . "'";
        $keys[] = 'userPassword';
        $vals[] = "'" . addslashes($user->_GET['password']) . "'";
        $keys[] = 'userCookieId';
        $vals[] = "'" . ($cookie_id = make_random_id()) . "'";
        $keys[] = 'userCookiePassword';
        $vals[] = "'" . ($cookie_pass = make_random_id()) . "'";
        $keys = implode(',', $keys);
        $vals = implode(',', $vals);
        mysql_query("INSERT INTO Users ($keys) VALUES ($vals)");
        $user->tell('<cookie>removeguest</cookie>');
        $user->_COOKIE[DPSERVER_COOKIE_NAME] = "$cookie_id;$cookie_pass";
        $user->tell('<cookie>' . $user->_COOKIE[DPSERVER_COOKIE_NAME] . '</cookie>');
        $user->addId($username);
        $user->setTitle(ucfirst($username));
        $user->addProperty('is_registered');
        $user->removeProperty('is_admin');
        $universe->mrCurrentDpUserRequest->mUsername = $username;

        foreach ($universe->mDpUsers as $user_nr => &$u) {
            if ($u[0] === $user) {
                $universe->mDpUsers[$user_nr][2] = $username;
                $universe->mDpUsers[$user_nr][3] = $cookie_id;
                $universe->mDpUsers[$user_nr][4] = $cookie_pass;
                $universe->mDpUsers[$user_nr][5] = 1;
            }
        }
        $user->tell('<changeDpElement id="username">' . $username
            . '</changeDpElement>');
        $user->tell(array('abstract' => '<changeDpElement id="'
            . $user->getUniqueId() . '"><b>'
            . $user->getAppearance(1, FALSE) . '</b></changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '"><b>'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</b></changeDpElement>'));
        $user->tell('<changeDpElement id="loginlink"><a href="'
            . DPSERVER_CLIENT_URL . '?location=' . DPUNIVERSE_PAGE_PATH
            . 'login.php&amp;act=logout" style="padding-left: 4px">'
            . dptext('Logout') . '</a></changeDpElement>');
        $this->tell(array('abstract' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</changeDpElement>'), $user);
        $user->tell('<window><h1>'
            . dptext('Thank you for registering and welcome to DutchPIPE!')
            . '</h1><br />'
            . sprintf(dptext('You are now logged in as: <b>%s</b>'), $username)
            . '</window>');
    }

    private function validUsername($userName, $password, $password2)
    {
        $this->mLastNewUserErrors = array();
        $len = strlen($userName);

        if (0 === $len) {
            $this->mLastNewUserErrors[] = '<li>'
                . dptext('No username was given') . '</li>';
        } else {
            if ($len < DPUNIVERSE_MIN_USERNAME_LEN) {
                $this->mLastNewUserErrors[] = '<li>' . sprintf(
                    dptext('The username must be at least %d characters long'),
                    DPUNIVERSE_MIN_USERNAME_LEN) . '</li>';
            } elseif ($len > DPUNIVERSE_MAX_USERNAME_LEN) {
                $this->mLastNewUserErrors[] = '<li>' . sprintf(
                    dptext('The username must be at most %d characters long'),
                    DPUNIVERSE_MAX_USERNAME_LEN) . '</li>';
            }

            /*if (FALSE !== ($words = file(DUTCHPIPE_FORBIDDEN_USERNAMES_FILE))
                    && count($words)) {
                foreach ($words as $word) {
                    if (FALSE !== strpos($userName, $word)) {
                        $this->lastUsernameError[] = '<li>' .
                        dptext('This username is not allowed, please try again.')
                        . '</li>';
                        break;
                    }
                }
            }*/

            $lower_user_name = strtolower($userName);
            if ($lower_user_name{0} < 'a' || $lower_user_name{0} > 'z') {
                $this->mLastNewUserErrors[] = '<li>'
                     . dptext('Illegal character in username at position 1 (usernames must start with a letter, digits or other characters are not allowed)')
                     . '</li>';
            }
            for ($i = 1; $i < $len; $i++) {
                if (($lower_user_name{$i} < 'a' || $lower_user_name{$i} > 'z')
                        && ($lower_user_name{$i} < '0'
                        || $lower_user_name{$i} > '9')) {
                    $this->mLastNewUserErrors[] = '<li>' . sprintf(
                        dptext('Illegal character in username at position %d (you can only use a-z and 0-9)'),
                        ($i + 1)) . '</li>';
                    break;
                }
            }

            $result = mysql_query("SELECT userId FROM Users WHERE "
                . "userUsernameLower='" . strtolower($userName) . "'");
            if ($result && mysql_num_rows($result)) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dptext('That username is already in use') . '</li>';
            }
        }

        if (!isset($password) || !strlen($password)) {
            $this->mLastNewUserErrors[] = '<li>'
                . dptext('No password was given') . '</li>';
        }

        if (0 === sizeof($this->mLastNewUserErrors)) {
            if (strlen($password) < 6) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dptext('Your password must be at least 6 characters long')
                    . '</li>';
            } elseif (strlen($password) > 32) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dptext('Your password must be at most 32 characters long')
                    . '</li>';
            } elseif (!strlen($password2)) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dptext('You didn\'t repeat your password') . '</li>';
            } elseif ($password !== $password2) {
                $this->mLastNewUserErrors[] = '<li>'
                    . dptext('The repeated password was different') . '</li>';
            }
        }

        return 0 === sizeof($this->mLastNewUserErrors);
    }
}
?>
