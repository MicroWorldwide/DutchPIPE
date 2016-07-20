<?php
/**
 * Replacements for gettext and _ if gettext is not enabled in PHP
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
 * @version    Subversion: $Id: dpfunctions.php 15 2006-05-18 21:50:46Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php
 */

if (!DPSERVER_GETTEXT_ENABLED || !function_exists('gettext')) {
    /**
     * Looks up an optionally formatted message in the current domain for
     * translation
     *
     * All texts in the DutchPIPE source code should be passed through this
     * function in English. This allows DutchPIPE to return translated strings
     * for DUtchPIPE sites in other languages.
     *
     * The message can be a plain string, or a formatted string according to
     * the sprintf format, see http://www.php.net/sprintf. In the last case more
     * arguments must be given to fill in values, as seen in the sprintf
     * documentation. If you need to translate a string and have it formatted
     * without extra arguments, just use sprintf directly.
     *
     * If gettext is enabled and the current domain is not equal to language of
     * the given string, a translated string will be returned if available.
     *
     * @param      string    $message    the integer of string to check
     * @param      mixed     $args,...   the integer of string to check
     * @return     string    string produced according to format and language
     */
    function dptext($message)
    {
        return $message;
    }
} else {
    if (!isset($language)) {
        /* :TODO: Check Windows/other *NIXes */
        putenv('LANGUAGE=' . DPSERVER_LOCALE_FULL);
        putenv('LANG=' . DPSERVER_LOCALE_FULL);
        setlocale(LC_ALL, DPSERVER_LOCALE_FULL);
        bindTextDomain(DPSERVER_GETTEXT_DOMAIN,
            DPSERVER_GETTEXT_LOCALE_PATH);
        textDomain(DPSERVER_GETTEXT_DOMAIN);
        bind_textdomain_codeset(DPSERVER_GETTEXT_DOMAIN,
            DPSERVER_GETTEXT_ENCODING);
    }

    /**
     * @ignore
     */
    function dptext($message)
    {
        return gettext($message);
    }
}
?>
