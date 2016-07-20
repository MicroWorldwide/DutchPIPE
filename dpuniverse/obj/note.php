<?php
/**
 * A small note that users can read
 *
 * DutchPIPE version 0.2; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_obj
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: note.php 243 2007-07-08 16:26:23Z ls $
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
 * @subpackage dpuniverse_obj
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
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
        $this->title = dptext('small note');
        $this->titleDefinite = dptext('the small note');
        $this->titleIndefinite = dptext('a small note');
        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'smallnote.gif';
        $this->addId(explode('#',
            dptext('note#paper note#small note#small paper note#small, paper note#a small note')));
        $this->body =
            dptext('This is a small paper note. You can read it.<br />');

        $this->value = 0.99;

        if (WEIGHT_TYPE_NONE !== WEIGHT_TYPE) {
            $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            if (WEIGHT_TYPE_ABSTRACT === WEIGHT_TYPE) {
                $this->weight = 1;
            } elseif (WEIGHT_TYPE_METRIC === WEIGHT_TYPE) {
                $this->weight = 3; /* Grams */
            } elseif (WEIGHT_TYPE_USA === WEIGHT_TYPE) {
                $this->weight = 0.1; /* Ounces */
            }
        }
        if (VOLUME_TYPE_NONE !== VOLUME_TYPE) {
            $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            if (VOLUME_TYPE_ABSTRACT === VOLUME_TYPE) {
                $this->volume = 1;
            } elseif (VOLUME_TYPE_METRIC === VOLUME_TYPE) {
                $this->volume = 5;
            } elseif (VOLUME_TYPE_USA === VOLUME_TYPE) {
                $this->volume = 0.17;
            }
        }

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
        /* Replies to object entering "read" */
        if (!strlen($noun)) {
            get_current_dpobject()->setActionFailure(
                dptext('What do you want to read?<br />'));
            return FALSE;
        }

        /* Replies to object entering "read noet" (typo) */
        if (FALSE === $this->isId($noun)) {
            get_current_dpobject()->setActionFailure(dptext('Read WHAT?<br />'));
            return FALSE;
        }

        /* Shows a nice window with content */
        get_current_dpobject()->tell('<window>' . dptext('Hello world.')
            . '</window>');
        return TRUE;
    }
}
?>
