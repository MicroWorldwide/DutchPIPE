<?php
/**
 * A 'non playing character', a bot
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
 * @version    Subversion: $Id: DpNpc.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpLiving
 */

/**
 * Builts upon the standard DpLiving class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpLiving.php');

/**
 * A 'non playing character', a bot
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpLiving
 */
class DpNpc extends DpLiving
{
    /**
     * Creates this "living" at object creation time
     */
    function createDpLiving()
    {
        // Standard setup calls to set some default values:
        $this->addId('npc');
        $this->setTitle("A NPC", DPUNIVERSE_TITLE_TYPE_INDEFINITE,
            DPUNIVERSE_IMAGE_URL . 'npc.gif');
        $this->addProperty('is_npc');

        // Call CreateDpNpc for objects that extend on this object:
        if (method_exists($this, 'createDpNpc')) {
            $this->createDpNpc();
        }
    }

    function resetDpLiving()
    {
        if (method_exists($this, 'resetDpNpc')) {
            $this->resetDpNpc();
        }
    }

    /**
     * Tells data (message, window, location, ...) to this NPC
     *
     * Tells a message to this object, for instance a chat line.
     *
     * @param      string    $data      message string
     */
    function tell($data, &$from = NULL)
    {
        if (empty($data)) {
            return;
        }

        if (is_array($data)) {
            $data = $data[$this->getProperty('display_mode')];
        }
        if (strlen($data) >=3 && substr($data, 0, 1) == '<'
                && FALSE !== ($pos = strpos($data, '>'))) {
            $type = substr($data, 1, $pos - 1);
            $endpos = strrpos($data, '<');
            $data = "<$type><![CDATA[" . substr($data, strlen($type) + 2,
                $endpos - strlen($type) - 2) . ']]>' . substr($data, $endpos);
        } else {
            $type = 'message';
            $data = "<message><![CDATA[$data]]></message>";
        }

        if (strlen($data) > 19
                && FALSE !== ($pos1 = strpos($data, '<location><![CDATA['))
                && FALSE !== ($pos2 = strpos($data, ']]></location>'))
                && $pos2 > $pos1 + 14) {
            $data = substr($data, 0, $pos2);
            $data = substr($data, $pos1 + 19);
            $newlocation = $data;
            if ('/' === $newlocation) {
                $newlocation = DPUNIVERSE_PAGE_PATH . 'index.php';
            }
            $newlocation = get_current_dpuniverse()->getDpObject($newlocation);
            if (FALSE === ($env = $this->getEnvironment())
                    || $env !== $newlocation) {
                if (!$env) {
                    $from_where = 'enters the site';
                } else {
                    $env->tell(ucfirst($this->getTitle()) . ' leaves to '
                        . $newlocation->getTitle() . '.<br />', $this);
                    $from_where = 'arrives from ' . $env->getTitle();
                }
                $this->moveDpObject($newlocation);
                $newlocation->tell(ucfirst($this->getTitle())
                    . " $from_where.<br />", $this);
            }
        }
    }
}
?>
