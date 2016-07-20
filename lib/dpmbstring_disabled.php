<?php
/**
 * Maps dp_* to mb_* multibyte functions
 *
 * Used when DPSERVER_ENABLE_MBSTRING is set to FALSE in dpserver-ini.php or
 * the multibyte extension is disabled in PHP.
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
 * @version    Subversion: $Id: dpmbstring_disabled.php 287 2007-08-21 18:47:19Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        http://www.php.net/manual/en/ref.mbstring.php, dpserver-ini.php,
 *             dpmbstring_enabled.php
 * @since      DutchPIPE 0.4.0
 */

/**
 * Sends an email
 *
 * @param      string  $to          Receiver or receivers of the mail
 * @param      string  $subject     Subject of the email to be sent
 * @param      string  $message     Message to be sent
 * @param      string  $addHeaders  Optional, inserted at end of email header
 * @param      string  $addPara     Optional, mail program parameters
 * @return     boolean TRUE on success or FALSE on failure
 * @see        http://www.php.net/manual/en/function.mail.php,
 *             http://www.php.net/manual/en/function.mb-send-mail.php
 * @ignore
 */
function dp_mail()
{
    $args = func_get_args();
    return call_user_func_array('mail', $args);
}

/**
 * Gets string length
 *
 * @param      string  $string      The string being measured for length
 * @param      string  $encoding    Ignored when given
 * @return     integer length of the given string
 * @see        http://www.php.net/manual/en/function.strlen.php,
 *             http://www.php.net/manual/en/function.mb-strlen.php
 * @ignore
 */
function dp_strlen($string)
{
    return strlen($string);
}

/**
 * Finds position of first occurrence of a string
 *
 * @param      string  $haystack    The string to search in
 * @param      string  $needle      The string to search for
 * @param      string  $offset      Optional search offset, default is 0
 * @param      string  $encoding    Ignored when given
 * @return     mixed   Position as an integer, or FALSE if needle was not found
 * @see        http://www.php.net/manual/en/function.strpos.php,
 *             http://www.php.net/manual/en/function.mb-strpos.php
 * @ignore
 */
function dp_strpos($haystack, $needle, $offset = NULL)
{
    return is_null($offset)
        ? strpos($haystack, $needle) : strpos($haystack, $needle, $offset);
}

/**
 * Finds position of last occurrence of a string
 *
 * @param      string  $haystack    The string to search in
 * @param      string  $needle      The string to search for
 * @param      string  $offset      Optional search offset, default is 0
 * @param      string  $encoding    Ignored when given
 * @return     mixed   Position as an integer, or FALSE if needle was not found
 * @see        http://www.php.net/manual/en/function.strrpos.php,
 *             http://www.php.net/manual/en/function.mb-strrpos.php
 * @ignore
 */
function dp_strrpos($haystack, $needle, $offset = NULL)
{
    return is_null($offset)
        ? strrpos($haystack, $needle) : strrpos($haystack, $needle, $offset);
}

/**
 * Gets part of a string
 *
 * @param      string  $string      The input string
 * @param      string  $start       Start position
 * @param      string  $length      Optional length modifier
 * @param      string  $encoding    Ignored when given
 * @return     string  Extracted part of string
 * @see        http://www.php.net/manual/en/function.substr.php,
 *             http://www.php.net/manual/en/function.mb-substr.php
 * @ignore
 */
function dp_substr($string, $start, $length = NULL)
{
    return is_null($length)
        ? substr($string, $start) : substr($string, $start, $length);
}

/**
 * @ignore
 */
function dp_strtolower($string)
{
    return strtolower($string);
}

/**
 * @ignore
 */
function dp_strtoupper($string)
{
    return strtoupper($string);
}

/**
 * @ignore
 */
function dp_substr_count($haystack, $needle)
{
    return substr_count($haystack, $needle);
}

/**
 * @ignore
 */
function dp_ereg($pattern, $string, $regs = NULL)
{
    return is_null($regs) ? ereg($pattern, $string)
        : ereg($pattern, $string, $regs);
}

/**
 * @ignore
 */
function dp_eregi($pattern, $string, $regs = NULL)
{
    return is_null($regs) ? eregi($pattern, $string)
        : eregi($pattern, $string, $regs);
}

/**
 * @ignore
 */
function dp_ereg_replace($pattern, $replacement, $string)
{
    return ereg_replace($pattern, $replacement, $string);
}

/**
 * @ignore
 */
function dp_eregi_replace($pattern, $replacement, $string)
{
    return eregi_replace($pattern, $replacement, $string);
}

/**
 * @ignore
 */
function dp_split($pattern, $string, $limit = NULL)
{
    return is_null($limit) ? split($pattern, $string)
        : split($pattern, $string, $limit);
}
?>
