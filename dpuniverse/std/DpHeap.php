<?php
/**
 * A heap of credits, stones, etc., represented by one object
 *
 * When instances of these objects get the same environment, they will merge.
 *
 * DutchPIPE version 0.1; PHP version 5
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
 * @version    Subversion: $Id: DpDrink.php 117 2006-08-30 15:18:12Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */

/**
 * Builts upon the standard DpObject class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpObject.php');

/**
 * A heap of credits, stones, etc., represented by one object
 *
 * When instances of these objects get the same environment, they will merge.
 *
 * Creates the following DutchPIPE properties:<br />
 *
 * - int <b>amount</b> - Number of units in heap
 * - string <b>isHeap</b> - Set to TRUE
 * - string <b>heapTitleSingular</b> - Title for single unit
 * - string <b>heapTitlePlural</b> - Title for more units
 * - int|float <b>heapWeightModifier</b> - Weight increase per unit
 * - int|float <b>heapVolumeModifier</b> - Volume increase per unit
 * - int|float <b>heapValueModifier</b> - Value increase per unit
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.0
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */
class DpHeap extends DpObject
{
    /**
     * Creates this heap object
     *
     * Called by DpObject when this object is created. Adds standard actions
     * which can be performed on this object.
     *
     * Calls {@link createDpHeap()} in the inheriting class.
     *
     * Adds integer or float "amount" property to this object, set to the amount
     * of items in the heap.
     *
     * @access     private
     * @see        createDpHeap()
     */
    final function createDpObject()
    {
        $this->amount = new_dp_property(1);
        $this->isHeap = new_dp_property(TRUE);
        $this->heapTitleSingular = new_dp_property(dptext('1 unit'));
        $this->heapTitlePlural = new_dp_property(dptext('%d units'));
        $this->heapWeightModifier = new_dp_property(1);
        $this->heapVolumeModifier = new_dp_property(1);
        $this->heapValueModifier = new_dp_property(0);

        $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');

        $this->createDpHeap();
    }

    /**
     * Sets this heap object up at the time it is created
     *
     * An empty function which can be redefined by the heap object extending on
     * DpHeap.
     *
     * @see        resetDpHeap()
     */
    function createDpHeap()
    {
    }

    /**
     * Resets this heap object
     *
     * Called by DpObject at regular intervals as defined in dpuniverse-ini.php.
     * Calls the method 'resetDpHeap' in this heap object. You can redefine
     * that function to periodically do stuff such as alter the state of this
     * heap object.
     *
     * @access     private
     * @see        resetDpHeap()
     */
    final function resetDpObject()
    {
        $this->resetDpHeap();
    }

    /**
     * Resets this heap object
     *
     * Called by this heap object at regular intervals as defined in
     * dpuniverse-ini.php. An empty function which can be redefined by the
     * heap object extending on DpDrink. To be used to periodically do stuff
     * such as alter the state of the heap object.
     *
     * @see        createDpHeap()
     */
    function resetDpHeap()
    {
    }

    /**
     * Moves this object into the inventory of another object
     *
     * @param   mixed   &$target_ob path or object to move into to
     * @param   boolean $simple     skip some checks
     * @return  int     FALSE for success, an error code for failure
     */
    function moveDpObject(&$target_ob, $simple = FALSE, $heap_amount = FALSE)
    {
        if (FALSE !== $heap_amount) {
            if ($heap_amount <= 0 || $heap_amount > $this->amount) {
                return E_MOVEOBJECT_BADHEAP;
            }
            if ($heap_amount == $this->amount) {
                $heap_amount = FALSE;
            }
        }

        $inv = $target_ob->getInventory();
        foreach ($inv as &$ob) {
            if ($ob !== $this && $ob->location === $this->location) {
                if (FALSE === $heap_amount) {
                    $ob->amount += $this->amount;
                    $this->removeDpObject();
                } else {
                    $ob->amount += $heap_amount;
                    $this->amount -= $heap_amount;
                }
                return FALSE;
            }
        }

        if (FALSE === $heap_amount) {
            return DpObject::moveDpObject($target_ob, $simple);
        }


        $newheap = get_current_dpuniverse()->newDpObject($this->location);
        $newheap->amount = $heap_amount;
        $newheap_move_result = $newheap->moveDpObject($target_ob, $simple);

        if (FALSE !== $newheap_move_result) {
            $newheap->removeDpObject();
        } else {
            $this->amount -= $heap_amount;
        }
        return $newheap_move_result;
    }

    /**
     * Checks if the given id is a valid id for this object
     *
     * @param      string    $id                name string to check
     * @param      string    $checkWithArticle  also check ids with articles
     * @return     boolean   TRUE if the id is valid, FALSE otherwise
     * @see        DpObject::isId()
     */
    function isId($id, $checkWithArticle = TRUE)
    {
        if (DpObject::isId($id)) {
            return TRUE;
        }
        return strlen($id)
            && preg_match(dptext("/^(\d+) (.+)$/"), $id, $matches)
            && DpObject::isId($matches[2]) && $matches[1] > 0
            && $matches[1] <= $this->amount;
    }

    /**
     * Sets the amount of items in the heap
     *
     * @param      string    $amount     new number of items in the heap
     * @return     bool      TRUE for success, FALSE for failure
     */
    protected function setAmount($amount)
    {
        if (0 == $amount) {
            return FALSE;
        }

        $this->setDpProperty('amount', $amount);

        $this->setTitle((1 == $amount ? $this->heapTitleSingular
            : sprintf($this->heapTitlePlural, $amount)),
            DPUNIVERSE_TITLE_TYPE_NAME);

        $this->weight = $amount * $this->heapWeightModifier;
        $this->volume = $amount * $this->heapVolumeModifier;
        $this->value = $amount * $this->heapValueModifier;

        if (FALSE !== ($env = $this->getEnvironment())) {
            $env->tell(array('abstract' =>
                '<changeDpElement id="'
                . $this->getUniqueId() . '">'
                . $this->getAppearance(1, FALSE) . '</changeDpElement>',
                'graphical' => '<changeDpElement id="'
                . $this->getUniqueId() . '">'
                . $this->getAppearance(1, FALSE, $this, 'graphical')
                . '</changeDpElement>'), $this);
        }

        return TRUE;
    }
}

?>
