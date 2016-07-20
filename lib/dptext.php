<?php
/**
 * Replacements for gettext and _ if gettext is not enabled in PHP
 *
 * DutchPIPE version 0.3; PHP version 5
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
 * @version    Subversion: $Id: dptext.php 252 2007-08-02 23:30:58Z ls $
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
     * for DutchPIPE sites in other languages.
     *
     * The message can be a plain string, or a formatted string according to
     * the sprintf format, see http://www.php.net/sprintf. In the last case more
     * arguments must be given to fill in values, as seen in the sprintf
     * documentation.
     *
     * If gettext is enabled and the set language is not equal to language of
     * the given string, a translated string will be returned if available in
     * the translation table.
     *
     * @param      string    $message    the integer or string to check
     * @param      mixed     $args,...   the integer or string to check
     * @return     string    string produced according to format and language
     */
    function dptext($message)
    {
        if (1 === func_num_args()) {
            return $message;
        }
        $args = func_get_args();
        $args = array_slice($args, 1);
        return vsprintf($message, $args);
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
        if (1 === func_num_args()) {
            return gettext($message);
        }

        $args = func_get_args();
        return vsprintf(gettext($message), array_slice($args, 1));
    }
}
?>
