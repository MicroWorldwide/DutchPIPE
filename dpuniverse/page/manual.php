<?php
/**
 * A DutchPIPE Manual page
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
 * @version    Subversion: $Id: index.php 22 2006-05-30 20:40:55Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpPage
 */

/**
 * Builts upon the standard DpPage class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpPage.php');

/**
 * The Home page
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
final class Manual extends DpPage
{
    /**
     * Sets up the page at object creation time
     */
    public function createDpPage()
    {
        if (FALSE === ($sublocation = $this->getProperty('sublocation'))) {
            $this->removeDpObject();
            return;
        }
        $this->setTemplateFile(DPSERVER_TEMPLATE_PATH . 'dpmanual.tpl');
        $this->addProperty('is_layered', TRUE);
        $this->setBody(dptext('/manual/' . $sublocation), 'file');

        $body = $this->getBody();
        $title = 'No title';
        $pos = strpos($body, $teststr = '<h1>');
        if (FALSE === $pos) {
            $pos = strpos($body, $teststr = '<h1 class="ref-title">');
        }
        if (FALSE !== $pos) {
            $body = substr($body, $pos + strlen($teststr));
            $pos = strpos($body, '</h1>');
            if (FALSE !== $pos) {
                $title = substr($body, 0, $pos);
            }
        }
        $this->setTitle($title);

        if ('index.html' === $sublocation) {
            $this->setNavigationTrail(
                array(DPUNIVERSE_NAVLOGO, '/'),
                'Documentation');
        } else {
            $this->setNavigationTrail(
                array(DPUNIVERSE_NAVLOGO, '/'),
                array('Documentation',
                    '/page/manual.php&sublocation=index.html'),
                $title);
        }
    }

    public function getBody()
    {
        $body = DpPage::getBody();

        $body = str_replace(
            array(
                '<a href="/manual/index.html',
                '<a href="DutchPIPE/',
                '<a href="../../',
                '<a href="../',
                '<a href="ric',
                '<a href="todolist',
                '<a href="classtrees',
                '<a href="elementindex',
                '"/jumptop.js"',
                '"/images/'
            ),
            array(
                '<a href="dpclient.php?location=/page/manual.php&sublocation=index.html',
                '<a href="dpclient.php?location=/page/manual.php&sublocation=DutchPIPE/',
                '<a href="dpclient.php?location=/page/manual.php&sublocation=',
                '<a href="dpclient.php?location=/page/manual.php&sublocation=',
                '<a href="dpclient.php?location=/page/manual.php&sublocation=ric',
                '<a href="dpclient.php?location=/page/manual.php&sublocation=todolist',
                '<a href="dpclient.php?location=/page/manual.php&sublocation=classtrees',
                '<a href="dpclient.php?location=/page/manual.php&sublocation=elementindex',
                '"jumptop.js"',
                '"images/'
                ),
            $body);

        return $body;
    }
}
?>
