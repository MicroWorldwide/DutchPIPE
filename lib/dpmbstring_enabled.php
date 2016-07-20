<?php
/**
 * Maps dp_* to mb_* multibyte functions
 *
 * Used when DPSERVER_ENABLE_MBSTRING is set to TRUE in dpserver-ini.php and
 * the multibyte extension is enabled in PHP.
 *
 * DutchPIPE version 0.4; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage lib
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpmbstring_enabled.php 287 2007-08-21 18:47:19Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        http://www.php.net/manual/en/ref.mbstring.php, dpserver-ini.php,
 *             dpmbstring_disabled.php
 * @since      DutchPIPE 0.4.0
 */

/**
 * Sends an email
 *
 * @param      string  $to          Receiver or receivers of the mail
 * @param      string  $subject     Subject of the email to be sent
 * @param      string  $message     Message to be sent
 * @param      string  $addHeaders  Optional, inserted at end of email header
 * @param      string  $addPara     Optional mail program parameters
 * @return     boolean TRUE on success or FALSE on failure
 * @see        http://www.php.net/manual/en/function.mail.php,
 *             http://www.php.net/manual/en/function.mb-send-mail.php
 */
function dp_mail()
{
    $args = func_get_args();
    return call_user_func_array('mb_send_mail', $args);
}

/**
 * Gets string length
 *
 * @param      string  $string      The string being measured for length
 * @param      string  $encoding    Optional character encoding
 * @return     integer length of the given string
 * @see        http://www.php.net/manual/en/function.strlen.php,
 *             http://www.php.net/manual/en/function.mb-strlen.php
 */
function dp_strlen($string, $encoding = NULL)
{
    return is_null($encoding) ? mb_strlen($string)
        : mb_strlen($string, $encoding);
}

/**
 * Finds position of first occurrence of a string
 *
 * @param      string  $haystack    The string to search in
 * @param      string  $needle      The string to search for
 * @param      string  $offset      Optional search offset, default is 0
 * @param      string  $encoding    Optional character encoding
 * @return     mixed   Position as an integer, or FALSE if needle was not found
 * @see        http://www.php.net/manual/en/function.strpos.php,
 *             http://www.php.net/manual/en/function.mb-strpos.php
 */
function dp_strpos($haystack, $needle, $offset = NULL, $encoding = NULL)
{
    return is_null($encoding)
        ? (is_null($offset) ? mb_strpos($haystack, $needle)
        : mb_strpos($haystack, $needle, $offset))
        : mb_strpos($haystack, $needle, $offset, $encoding);
}

/**
 * Finds position of last occurrence of a string
 *
 * @param      string  $haystack    The string to search in
 * @param      string  $needle      The string to search for
 * @param      string  $offset      Optional search offset, default is 0
 * @param      string  $encoding    Optional character encoding
 * @return     mixed   Position as an integer, or FALSE if needle was not found
 * @see        http://www.php.net/manual/en/function.strrpos.php,
 *             http://www.php.net/manual/en/function.mb-strrpos.php
 */
function dp_strrpos($haystack, $needle, $offset = 0, $encoding = NULL)
{
    return is_null($encoding)
        ? (is_null($offset) ? mb_strrpos($haystack, $needle)
        : mb_strrpos($haystack, $needle, $offset))
        : mb_strrpos($haystack, $needle, $offset, $encoding);
}

/**
 * Gets part of a string
 *
 * @param      string  $string      The input string
 * @param      string  $start       Start position
 * @param      string  $length      Optional length modifier
 * @param      string  $encoding    Optional character encoding
 * @return     string  Extracted part of string
 * @see        http://www.php.net/manual/en/function.substr.php,
 *             http://www.php.net/manual/en/function.mb-substr.php
 */
function dp_substr($string, $start, $length = NULL, $encoding = NULL)
{
    return is_null($encoding) ? (is_null($length) ? mb_substr($string, $start)
        : mb_substr($string, $start, $length))
        : mb_substr($string, $start, $length, $encoding);
}

function dp_strtolower($string, $encoding = NULL)
{
    return is_null($encoding) ? mb_strtolower($string)
        : mb_strtolower($string, $encoding);
}

function dp_strtoupper($string, $encoding = NULL)
{
    return is_null($encoding) ? mb_strtoupper($string)
        : mb_strtoupper($string, $encoding);
}

function dp_substr_count($haystack, $needle, $encoding = NULL)
{
    return is_null($encoding) ?
        mb_substr_count($haystack, $needle)
        : mb_substr_count($haystack, $needle, $encoding);
}

function dp_ereg($pattern, $string, $regs = NULL)
{
    return is_null($regs) ? mb_ereg($pattern, $string)
        : mb_ereg($pattern, $string, $regs);
}

function dp_eregi($pattern, $string, $regs = NULL)
{
    return is_null($regs) ? mb_eregi($pattern, $string)
        : mb_eregi($pattern, $string, $regs);
}


function dp_ereg_replace($pattern, $replacement, $string, $option = NULL)
{
    return is_null($option) ? mb_ereg_replace($pattern, $replacement, $string)
        : mb_ereg_replace($pattern, $replacement, $string, $option);
}

function dp_eregi_replace($pattern, $replacement, $string, $option = NULL)
{
    return is_null($option) ? mb_eregi_replace($pattern, $replacement, $string)
        : mb_eregi_replace($pattern, $replacement, $string, $option);
}

function dp_split($pattern, $string, $limit = NULL)
{
    return is_null($limit) ? mb_split($pattern, $string)
        : mb_split($pattern, $string, $limit);
}
?>
