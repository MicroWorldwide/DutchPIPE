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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: login.php 5 2006-05-16 21:07:08Z ls $
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
        $this->setTitle("Login/register");
        $this->setBody(DPUNIVERSE_PAGE_PATH . 'login.html', 'file');
        $this->setNavigationTrail(
            array(DPUNIVERSE_NAVLOGO, '/'),
            'Login/register');
    }

    public function getBody($str = NULL)
    {

        $universe = get_current_dpuniverse();

        if (($user = get_current_dpuser()) && isset($user->_GET['act'])
                && $user->_GET['act'] == 'logout'
                && $user->getProperty('is_registered')) {
            echo "LOGGING OUT\n";
            $username = 'Guest#' . $universe->getGuestCnt();
            $universe->guest_counter++;
            $cookie_id = make_random_id();
            $cookie_pass = make_random_id();
            $oldtitle = $user->getTitle();
            $user->tell('<cookie>removeregistered</cookie>');
            $user->_COOKIE['dutchpipe'] = "$cookie_id;$cookie_pass";
            $universe->tellCurrentDpUserRequest("Set-Login: "
                . $user->_COOKIE['dutchpipe']);
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
            $user->tell('<window><h1>Logged out ' . $oldtitle
                . '</h1><br />See you later!<br />You are now: <b>'
                . $user->getTitle() . '</b></window>');
            $universe->mNoDirectTell = FALSE;
            $user->tell('<location>' . DPUNIVERSE_PAGE_PATH . 'login.php</location>');


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
            $this->mLastNewUserErrors[] = '<li>No username was given</li>';
        }
        if (0 === sizeof($this->mLastNewUserErrors)
                && $user->_GET['username'] === $user->getTitle()) {
            $this->mLastNewUserErrors[] = '<li>You are already logged in as '
                . $user->getTitle() . '</li>';
        } elseif (!isset($user->_GET['password'])
                || 0 === strlen($user->_GET['password'])) {
            $this->mLastNewUserErrors[] = '<li>No password was given</li>';
        }
        if (0 === sizeof($this->mLastNewUserErrors)) {
            $username = addslashes($user->_GET['username']);
            $result = mysql_query("SELECT userUsername, userPassword, "
                . "userCookieId, userCookiePassword from Users where "
                . "userUsernameLower='" . strtolower($username) . "'");
            if (empty($result) || !($row = mysql_fetch_array($result))) {
                $this->mLastNewUserErrors[] =
                    '<li>That username doesn\'t exist</li>';
            } else {
                if ($row[1] !== $user->_GET['password']) {
                    $this->mLastNewUserErrors[] = '<li>Invalid password</li>';
                }
            }
        }

        if (sizeof($this->mLastNewUserErrors)) {
            $user->tell('<window styleclass="dpwindow_error"><h1>Invalid '
                . 'login</h1><br /><ul>'
                . implode('', $this->mLastNewUserErrors) . '</ul></window>');
            return FALSE;
        }

        $username = $row[0];
        $cookie_id = $row[2];
        $cookie_pass = $row[3];
        $user->tell('<cookie>removeguest</cookie>');
        $user->_COOKIE['dutchpipe'] =
            "$cookie_id;$cookie_pass";
        $user->tell('<cookie>' . $user->_COOKIE['dutchpipe'] . '</cookie>');
        $user->_GET['username'] = $username;
        $user->addId($user->_GET['username']);
        $user->addId(strtolower($user->_GET['username']));
        $user->setTitle(ucfirst($user->_GET['username']));
        $user->addProperty('is_registered');
        $universe->mrCurrentDpUserRequest->mUsername =
            $user->_GET['username'];
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
        $user->tell('<changeDpElement id="loginlink"><a href="/dpclient.php?'
            . 'location=' . DPUNIVERSE_PAGE_PATH . 'login.php&amp;act=logout" style="padding-left: '
            . '4px">Logout</a></changeDpElement>');
        $this->tell(array('abstract' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</changeDpElement>'), $user);
        $user->tell('<window><h1>Welcome back</h1><br />You are now logged in '
            . 'as: <b>' . $user->getTitle() . '</b></window>');
        return TRUE;
    }

    function validateNewUser()
    {
        $universe = get_current_dpuniverse();

        $user = get_current_dpuser();

        if (FALSE === $this->validUsername($user->_GET['username'],
                $user->_GET['password'], $user->_GET['password2'])) {
            $user->tell('<window styleclass="dpwindow_error"><h1>Invalid '
                . 'registration</h1><br />Please correct the following '
                . 'errors:<ul>' . implode('', $this->mLastNewUserErrors)
                . '</ul></window>');
            return FALSE;
        } else {
            if (!isset($user->_GET['givencode'])) {
                if (FALSE === ($captcha_id =
                        $universe->getRandCaptcha())) {
                    return TRUE;
                }
                $user->tell('<window><form method="post" onsubmit="return '
                    . 'send_captcha(' . $captcha_id . ')"><div align="center">'
                    . '<img id="captchaimage" src="/dpcaptcha.php?captcha_id='
                    . $captcha_id . '" border="0" alt="" /></div>'
                    . '<br clear="all" />To complete registration, please '
                    . 'enter the code you see above:<br /><br />'
                    . '<div align="center" style="margin-bottom: 5px">'
                    . '<input id="givencode" type="text" size="6" maxlength="6" value="" /> '
                    . '<input type="submit" value="OK" /></div><br />'
                    . 'This system is used to filter software robots from '
                    . 'registrations submitted by individuals. If you are '
                    . 'unable to validate the above code, please <a href="'
                    . 'mailto:registration@dutchpipe.org">mail us</a> to '
                    . 'complete registration.</form></window>');
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
            $user->tell('<window styleclass="dpwindow_error"><h1>Failure '
                . 'validating code</h1><br />Please try again.</window>');
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
        $user->_COOKIE['dutchpipe'] =
            "$cookie_id;$cookie_pass";
        $user->tell('<cookie>' . $user->_COOKIE['dutchpipe'] . '</cookie>');
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
        $user->tell('<changeDpElement id="loginlink"><a href="/dpclient.php?'
            . 'location=' . DPUNIVERSE_PAGE_PATH . 'login.php&amp;act=logout" '
            . 'style="padding-left: 4px">Logout</a></changeDpElement>');
        $this->tell(array('abstract' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $user->getUniqueId() . '">'
            . $user->getAppearance(1, FALSE, $user, 'graphical')
            . '</changeDpElement>'), $user);
        $user->tell('<window><h1>Thank you for registering and welcome to '
            . 'DutchPIPE!</h1><br />You are now logged in as: <b>' . $username
            . '</b></window>');
    }

    private function validUsername($userName, $password, $password2)
    {
        $this->mLastNewUserErrors = array();
        $len = strlen($userName);

        if (0 === $len) {
            $this->mLastNewUserErrors[] = '<li>No username was given</li>';
        } else {

            if ($len < DPUNIVERSE_MIN_USERNAME_LEN) {
                $this->mLastNewUserErrors[] = '<li>The username must be at '
                    . 'least ' . DPUNIVERSE_MIN_USERNAME_LEN
                    . ' characters long</li>';
            } elseif ($len > DPUNIVERSE_MAX_USERNAME_LEN) {
                $this->mLastNewUserErrors[] = '<li>The username must be at '
                    . 'most ' . DPUNIVERSE_MAX_USERNAME_LEN +
                    ' characters long</li>';
            }

            /*if (FALSE !== ($words = file(DUTCHPIPE_FORBIDDEN_USERNAMES_FILE))
                    && count($words)) {
                foreach ($words as $word) {
                    if (FALSE !== strpos($userName, $word)) {
                        $this->lastUsernameError[] = '<li>This username is not '
                            . 'allowed, please try again.</li>';
                        break;
                    }
                }
            }*/
            $lower_user_name = strtolower($userName);
            if ($lower_user_name{0} < 'a' || $lower_user_name{0} > 'z') {
                $this->mLastNewUserErrors[] = '<li>Illegal character in '
                    . 'username at position 1 (usernames must start with a '
                    . 'letter, digits or other characters are not '
                    . 'allowed)</li>';
            }
            for ($i = 1; $i < $len; $i++) {
                if (($lower_user_name{$i} < 'a' || $lower_user_name{$i} > 'z')
                        && ($lower_user_name{$i} < '0'
                        || $lower_user_name{$i} > '9')) {
                    $this->mLastNewUserErrors[] = '<li>Illegal character in '
                        . 'username at position ' . ($i + 1) .
                        ' (you can only use a-z and 0-9)</li>';
                    break;
                }
            }

            $result = mysql_query("SELECT userId from Users where "
                . "userUsernameLower='" . strtolower($userName) . "'");
            if ($result && mysql_num_rows($result)) {
                $this->mLastNewUserErrors[] = '<li>That username is already in '
                    . 'use</li>';
            }
        }

        if (!isset($password) || !strlen($password)) {
            $this->mLastNewUserErrors[] = '<li>No password was given</li>';
        }

        if (0 === sizeof($this->mLastNewUserErrors)) {
            if (strlen($password) < 6) {
                $this->mLastNewUserErrors[] = '<li>Your password must be at '
                    . 'least 6 characters long</li>';
            } elseif (strlen($password) > 32) {
                $this->mLastNewUserErrors[] = '<li>Your password must be at '
                    . 'most 32 characters long</li>';
            } elseif (!strlen($password2)) {
                $this->mLastNewUserErrors[] = '<li>You didn\'t repeat your '
                    . 'password</li>';
            } elseif ($password !== $password2) {
                $this->mLastNewUserErrors[] = '<li>The repeated password was '
                    . 'different</li>';
            }
        }

        return 0 === sizeof($this->mLastNewUserErrors);
    }
}
?>
