<?php
/**
 * Credits object for monetary system
 *
 * DutchPIPE version 0.1; PHP version 5
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
 * @version    Subversion: $Id$
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpHeap
 */

/**
 * Builts upon the standard DpObject class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpHeap.php');

/**
 * Credits object for monetary system
 *
 * Creates the following DutchPIPE properties:<br />
 *
 * - boolean <b>isCredits</b> - Set to TRUE
 * - boolean <b>isNoSell</b> - Set to TRUE
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_obj
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://www.dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.0
 * @link       http://www.dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */
final class Credits extends DpHeap
{
    /**
     * Sets up the object at object creation time
     */
    public function createDpHeap()
    {
        $this->heapTitleSingular = dptext('1 credit');
        $this->heapTitlePlural = dptext('%d credits');

        $this->titleImg = DPUNIVERSE_IMAGE_URL . 'credits.gif';
        $this->addId(explode('#', dptext('credit#credits')));
        $this->setBody(dptext('It is real money!'));

        if (WEIGHT_TYPE_NONE !== WEIGHT_TYPE) {
            if (WEIGHT_TYPE_ABSTRACT === WEIGHT_TYPE) {
                $this->heapWeightModifier = 0.001;
            } elseif (WEIGHT_TYPE_METRIC === WEIGHT_TYPE) {
                $this->heapWeightModifier = 1;
            } elseif (WEIGHT_TYPE_USA === WEIGHT_TYPE) {
                $this->heapWeightModifier = 0.035;
            }
        }

        if (VOLUME_TYPE_NONE !== VOLUME_TYPE) {
            if (VOLUME_TYPE_ABSTRACT === VOLUME_TYPE) {
                $this->heapVolumeModifier = 0.001;
            } elseif (VOLUME_TYPE_METRIC === VOLUME_TYPE) {
                $this->heapVolumeModifier = 1;
            } elseif (VOLUME_TYPE_USA === VOLUME_TYPE) {
                $this->heapVolumeModifier = 0.035;
            }
        }

        $this->heapValueModifier = 1;
        $this->isCredits = new_dp_property(TRUE);
        $this->isNoSell = new_dp_property(TRUE);

        $this->amount = 1;
    }
}
?>

