<?php
/**
 * Common functions for templates available to universe objects and dpclient.php
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
 * @version    Subversion: $Id: dptemplates.php 277 2007-08-19 18:15:10Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse.php, dptemplates.php
 */

/**
 * Gets subtemplates for the default template or a given template file name
 *
 * Returns full paths for subtemplates. If no template file name is given with
 * $templatePath, the default dpdefault.tpl is used. The array with subtemplates
 * should contain snippets identying subtemplates, such as "input". The function
 * will then return the full path to dpdefault_info.tpl. If a non-default
 * template file is given, for example dpcustom.tpl, and the subtemplate for it
 * exists, the full path to that subtemplate, in the example dpcustom_info.tpl,
 * is returned instead.
 *
 * @access  private
 * @param   array   $subtemplates  array with subtemplates
 * @param   string  $templatePath  template file name
 * @return  array   array with pathnames to subtemplates
 */
function dp_get_subtemplates($subtemplates, $templatePath)
{
    $rvals = array();

    if ($templatePath) {
        $pos = dp_strrpos($templatePath, '.');
        if (FALSE !== $pos) {
            $prefix = substr($templatePath, 0, $pos);
            $postfix = substr($templatePath, $pos);
            foreach ($subtemplates as $subtemplate) {
                $rvals[$subtemplate] = $prefix . '_' . $subtemplate . $postfix;
                if (!file_exists($rvals[$subtemplate])) {
                    $rvals[$subtemplate] = FALSE;
                }
            }
        }
    }
    foreach ($subtemplates as $subtemplate) {
        if (empty($rvals[$subtemplate])) {
            $rvals[$subtemplate] = DPSERVER_TEMPLATE_PATH . 'dpdefault_'
                . $subtemplate . '.tpl';
        }
    }

    return $rvals;
}
?>
