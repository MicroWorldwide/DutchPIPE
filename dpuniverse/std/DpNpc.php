<?php
/**
 * A 'non playing character', a bot
 *
 * DutchPIPE version 0.4; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpNpc.php 278 2007-08-19 22:52:25Z ls $
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
 * Creates the following DutchPIPE properties:<br />
 *
 * - boolean <b>isNpc</b> - Set to TRUE
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpLiving
 */
class DpNpc extends DpLiving
{
    /**
     * Creates this NPC
     *
     * Called by DpLiving when this object is created.
     *
     * Calls {@link createDpNpc()} in the inheriting class.
     *
     * @access     private
     * @see        createDpNpc()
     */
    final function createDpLiving()
    {
        // Standard setup calls to set some default values:
        $this->addId(dp_text('npc'));
        $this->setTitle(dp_text('NPC'));
        $this->setTitleDefinite(dp_text('the NPC'));
        $this->setTitleIndefinite(dp_text('a NPC'));
        $this->setTitleImg(DPUNIVERSE_IMAGE_URL . 'npc.gif');
        $this->isNpc = new_dp_property(TRUE);

        // Call CreateDpNpc for objects that extend on this object:
        $this->createDpNpc();
    }

    /**
     * Sets this NPC up at the time it is created
     *
     * An empty function which can be redefined by the NPC class extending on
     * DpNpc. When the object is created, it has no title, HTML body, et cetera,
     * so in this method methods like {@link DpObject::setTitle()} are called.
     *
     * @see        resetDpNpc()
     */
    function createDpNpc()
    {
    }

    /**
     * Resets this NPC
     *
     * Called by DpLiving at regular intervals as defined in dpuniverse-ini.php.
     * Calls the method 'resetDpNpc' in this NPC. You can redefine that function
     * to periodically do stuff such as alter the state of this NPC.
     *
     * @access     private
     * @see        resetDpNpc()
     */
    final function resetDpLiving()
    {
        $this->resetDpNpc();
    }

    /**
     * Resets this NPC
     *
     * Called by this NPC at regular intervals as defined in dpuniverse-ini.php.
     * An empty function which can be redefined by the NPC class extending on
     * DpNpc. To be used to periodically do stuff such as alter the state of the
     * NPC.
     *
     * @see        createDpNpc()
     */
    function resetDpNpc()
    {
    }

    function eventDpLiving($name)
    {
        $args = func_get_args();
        call_user_func_array(array($this, 'eventDpNpc'), $args);
    }

    function eventDpNpc($name)
    {
    }

    /**
     * Tells data (message, window, location, ...) to this NPC
     *
     * Tells a message to this NPC, for instance a chat line or a new location.
     *
     * @param      string    $data      message string
     * @see        DpObject::tell(), DpUser::tell(), DpPage::tell()
     */
    function tell($data)
    {
        if (empty($data)) {
            return;
        }

        if (is_array($data)) {
            $data = $data[$this->displayMode];
        }
        if (dp_strlen($data) >=3 && dp_substr($data, 0, 1) == '<'
                && FALSE !== ($pos = dp_strpos($data, '>'))) {
            $type = dp_substr($data, 1, $pos - 1);
            $endpos = dp_strrpos($data, '<');
            $data = "<$type><![CDATA[" . dp_substr($data, dp_strlen($type) + 2,
                $endpos - dp_strlen($type) - 2) . ']]>'
                . dp_substr($data, $endpos);
        } else {
            $type = 'message';
            $data = "<message><![CDATA[$data]]></message>";
        }
        if (dp_strlen($data) > 19
                && FALSE !== ($pos1 = dp_strpos($data, '<location><![CDATA['))
                && FALSE !== ($pos2 = dp_strpos($data, ']]></location>'))
                && $pos2 > $pos1 + 14) {
            $data = dp_substr($data, 0, $pos2);
            $data = dp_substr($data, $pos1 + 19);
            $newlocation = $data;
            if (!$newlocation || '/' === $newlocation) {
                $newlocation = DPUNIVERSE_PAGE_PATH . 'index.php';
            }
            $newlocation = get_current_dpuniverse()->getDpObject($newlocation);
            if (FALSE === ($env = $this->getEnvironment())
                    || $env !== $newlocation) {
                if (!$env) {
                    $from_where = sprintf(dp_text("%s enters the site.<br />"),
                        ucfirst($this->getTitle(
                        DPUNIVERSE_TITLE_TYPE_DEFINITE)));
                } else {
                    $env->tell(sprintf(dp_text("%s leaves to %s.<br />"),
                        ucfirst($this->getTitle(
                        DPUNIVERSE_TITLE_TYPE_DEFINITE)),
                        $newlocation->getTitle()), $this);
                    $from_where = sprintf(dp_text("%s arrives from %s.<br />"),
                        ucfirst($this->getTitle(
                        DPUNIVERSE_TITLE_TYPE_DEFINITE)), $env->getTitle());
                }
                $this->moveDpObject($newlocation);
                $newlocation->tell($from_where, $this);
            }
        }
    }
}
?>
