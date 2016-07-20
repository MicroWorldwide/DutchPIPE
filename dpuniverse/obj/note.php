<?php
/**
 * A small note that users can read
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
 * @version    Subversion: $Id: note.php 22 2006-05-30 20:40:55Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */

/**
 * Builts upon the standard DpObject class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpObject.php');

/**
 * A small note that users can read
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */
final class Note extends DpObject
{
    /**
     * Sets up the object at object creation time
     */
    public function createDpObject()
    {
        $this->setTitle(dptext('small note'));
        $this->setTitleDefinite(dptext('the small note'));
        $this->setTitleIndefinite(dptext('a small note'));
        $this->setTitleImg(DPUNIVERSE_IMAGE_URL . 'smallnote.gif');
        $this->addId(explode('#',
            dptext('note#paper note#small note#small paper note#small, paper note#a small note')));
        $this->setBody(
            dptext('This is a small paper note. You can read it.<br />'));

        $this->addAction(dptext('read me!'), dptext('read'), 'actionRead');
    }

    /**
     * Performs the 'read' action
     *
     * @param   string  $verb   The name or verb part of the action, "read"
     * @param   string  $noun   The given id, noun or remainder, "note"
     * @return  boolean         FALSE in case of failure, TRUE for success
     */
    public function actionRead($verb, $noun)
    {
        /* Replies to user entering "read" */
        if (!strlen($noun)) {
            get_current_dpuser()->setActionFailure(
                dptext('What do you want to read?<br />'));
            return FALSE;
        }

        /* Replies to user entering "read noet" (typo) */
        if (FALSE === $this->isId($noun)) {
            get_current_dpuser()->setActionFailure(dptext('Read WHAT?<br />'));
            return FALSE;
        }

        /* Shows a nice window with content */
        get_current_dpuser()->tell('<window>' . dptext('Hello world.')
            . '</window>');
        return TRUE;
    }
}
?>
